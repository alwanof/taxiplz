<template>
    <div>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <button class="btn btn-success" data-target="#addFeed" data-toggle="modal" type="button">
                    <i class="fas fa-plus"></i>
                </button>
                <i class="fas fa-cog fa-spin px-2 text-primary" v-show="loading"></i>

                <div class="card-tools">
                    <div class="input-group input-group-sm">
                        <input
                            :placeholder=" local[lang+'.users']['search'] "
                            class="form-control float-right"
                            name="table_search"
                            type="text"
                            v-model="keywords"
                        />

                        <div class="input-group-append">
                            <button class="btn btn-default" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover table-head-fixed">
                    <thead>
                    <tr>
                        <th>{{ local[lang+".users"]["name"] }}</th>
                        <th>{{ local[lang+".users"]["guard_name"] }}</th>
                        <th>
                            <span class="badge badge-info">{{ permissions.total}}</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr :key="permission.id" v-for="permission in permissions.data">
                        <td>{{ permission.name }}</td>
                        <td>{{ permission.guard_name}}</td>
                        <td>
                            <button
                                @click="editpermission(permission)"
                                class="btn btn-sm btn-info text-white"
                                type="button"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button
                                @click="removepermission(permission)"
                                class="btn btn-sm btn-danger"
                                type="button"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <hr/>
                <div class="p-2">
                    <pagination :data="Object.assign({},permissions)" @pagination-change-page="getResults"></pagination>
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div
            aria-hidden="true"
            aria-labelledby="myModalLabel"
            class="modal fade"
            id="addFeed"
            permission="dialog"
            tabindex="-1"
        >
            <div class="modal-dialog" permission="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4
                            class="modal-title w-100 font-weight-bold"
                            v-show="permission.id==null"
                        >{{ local[lang+".users"]["create-permission"] }}</h4>
                        <h4
                            class="modal-title w-100 font-weight-bold"
                            v-show="permission.id!=null"
                        >{{ local[lang+".users"]["edit-permission"] }}</h4>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="addEditpermission">
                        <div class="modal-body mx-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-pen"></i>
                  </span>
                                </div>
                                <input
                                    :placeholder="local[lang+'.users']['name']"
                                    class="form-control"
                                    required
                                    type="text"
                                    v-model="permission.name"
                                />
                            </div>
                            <div class="text-danger" v-if="errors && errors.name">{{ errors.name[0] }}</div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-cog fa-spin" v-show="loading"></i>
                                <i class="fas fa-cog" v-show="!loading"></i>
                                <span class="px-1">{{ local[lang+".users"]["save"] }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CONFIG from "../app";

    export default {
        name: "permissionComponent.vue",
        props: ["auth", "lang"],
        data() {
            return {
                path: CONFIG.PATH,
                loading: false,
                permissions: [],
                local: CONFIG.LANG,
                permission: {
                    id: null,
                    name: null
                },
                keywords: null,
                errors: []
            };
        },
        created() {
            this.getResults();
        },
        watch: {
            keywords(after, before) {
                if (this.keywords.length > 2 || this.keywords.length === 0) {
                    this.search();
                }
            }
        },
        methods: {
            getResults(page = 1) {
                this.loading = true;
                if (typeof page === "undefined") {
                    page = 1;
                }
                axios
                    .get(
                        CONFIG.API_URL +
                        "permissions?page=" +
                        page +
                        "&api_token=" +
                        this.auth.api_token
                    )
                    .then(res => {
                        this.permissions = res.data;
                        this.loading = false;
                    });
            },
            search(page) {
                this.loading = true;
                if (typeof page === "undefined") {
                    page = 1;
                }
                axios
                    .get(
                        CONFIG.API_URL +
                        "search/permissions?page=" +
                        page +
                        "&keywords=" +
                        this.keywords +
                        "&api_token=" +
                        this.auth.api_token
                    )
                    .then(res => {
                        this.permissions = res.data;
                        this.loading = false;
                    });
            },
            addEditpermission(permission = null) {
                this.loading = true;
                if (this.permission.id) {
                    axios
                        .put(
                            CONFIG.API_URL +
                            "permissions/" +
                            this.permission.id +
                            "?api_token=" +
                            this.auth.api_token,
                            this.permission
                        )
                        .then(res => {
                            this.loading = false;
                            $("#addFeed").modal("hide");
                            toastr["success"](
                                this.local[this.lang + ".alerts"]["updated"],
                                this.local[this.lang + ".alerts"]["ok"]
                            );
                            this.getResults(1);
                            this.clearFields();
                        })
                        .catch(error => {
                            this.loading = false;
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors || {};
                            } else {
                                $("#addFeed").modal("hide");
                                toastr["error"](
                                    this.local[this.lang + ".alerts"]["error"],
                                    this.local[this.lang + ".alerts"]["err"]
                                );
                                this.clearFields();
                            }
                        });
                } else {
                    axios
                        .post(
                            CONFIG.API_URL +
                            "permissions" +
                            "?api_token=" +
                            this.auth.api_token,
                            this.permission
                        )
                        .then(res => {
                            this.loading = false;
                            $("#addFeed").modal("hide");
                            toastr["success"](
                                this.local[this.lang + ".alerts"]["added"],
                                this.local[this.lang + ".alerts"]["ok"]
                            );
                            this.getResults(1);
                            this.clearFields();
                        })
                        .catch(error => {
                            this.loading = false;
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors || {};
                            } else {
                                $("#addFeed").modal("hide");
                                toastr["error"](
                                    this.local[this.lang + ".alerts"]["error"],
                                    this.local[this.lang + ".alerts"]["err"]
                                );
                                this.clearFields();
                            }
                        });
                }
            },
            editpermission(permission) {
                this.permission.id = permission.id;
                this.permission.name = permission.name;
                this.permission.email = permission.email;
                $("#addFeed").modal("show");
            },
            removepermission(permission) {
                var conf = confirm(this.local[this.lang + ".alerts"]["confirm-delete"]);
                if (conf) {
                    this.loading = false;
                    axios
                        .delete(
                            CONFIG.API_URL +
                            "permissions/" +
                            permission.id +
                            "?api_token=" +
                            this.auth.api_token
                        )
                        .then(res => {
                            this.loading = false;
                            toastr["success"](
                                this.local[this.lang + ".alerts"]["removed"],
                                this.local[this.lang + ".alerts"]["ok"]
                            );
                            this.getResults(1);
                        })
                        .catch(error => {
                            this.loading = false;
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors || {};
                            } else {
                                toastr["error"](
                                    this.local[this.lang + ".alerts"]["error"],
                                    this.local[this.lang + ".alerts"]["err"]
                                );
                            }
                        });
                }

            },
            clearFields() {
                this.permission.name = null;
            }
        }
    };
</script>

<style scoped>
</style>
