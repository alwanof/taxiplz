<template>
    <div>
        <div class="row">
            <div :class="'card card-primary card-outline col-lg-'+roleSlide">
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
                                <span class="badge badge-info">{{roles.total}}</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr :key="role.id" v-for="(role,index) in roles.data">
                            <td>{{ role.name }}</td>
                            <td>{{role.guard_name}}</td>
                            <td>
                                <button
                                    @click="editrole(role)"
                                    class="btn btn-sm btn-info text-white"
                                    type="button"
                                >
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button @click="removerole(role)" class="btn btn-sm btn-danger" type="button">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button
                                    @click="expandPerm(role,index)"
                                    class="btn btn-sm btn-warning"
                                    type="button"
                                >
                                    <i class="fa fa-lock"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr/>
                    <div class="p-2">
                        <pagination :data="Object.assign({},roles)" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
            </div>
            <div
                :class="'card card-warning card-outline mx-1 col-lg-'+(11-roleSlide)"
                v-show="roleSlide==7"
            >
                <div class="card-header">
                    {{ local[lang+".users"]["roles"] }}/{{ cRoleName }}
                    <hr/>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input
                                :placeholder=" local[lang+'.users']['search'] "
                                class="form-control"
                                name="table_search"
                                type="text"
                                v-model="keywordsPerm"
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
                            <th>
                                {{ local[lang+".users"]["name"] }}
                                <span
                                    class="badge badge-info"
                                >{{ permissions.total}}</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr :key="permission.id" v-for="permission in permissions.data">
                            <td>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success"
                                >
                                    <input
                                        :id="'switch'+permission.name"
                                        @click="togglePerm(RID,permission)"
                                        class="custom-control-input"
                                        type="checkbox"
                                        v-bind:value="permission.id"
                                        v-model="cPerms"
                                    />
                                    <label
                                        :for="'switch'+permission.name"
                                        class="custom-control-label"
                                    >{{ permission.name }}</label>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <hr/>
                    <div class="p-2">
                        <pagination :data="Object.assign({},permissions)"
                                    @pagination-change-page="getResultsPerm"></pagination>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div
            aria-hidden="true"
            aria-labelledby="myModalLabel"
            class="modal fade"
            id="addFeed"
            role="dialog"
            tabindex="-1"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4
                            class="modal-title w-100 font-weight-bold"
                            v-show="role.id==null"
                        >{{ local[lang+".users"]["create-role"] }}</h4>
                        <h4
                            class="modal-title w-100 font-weight-bold"
                            v-show="role.id!=null"
                        >{{ local[lang+".users"]["edit-role"] }}</h4>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="addEditrole">
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
                                    v-model="role.name"
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
        name: "roleComponent.vue",
        props: ["auth", "lang"],
        data() {
            return {
                path: CONFIG.PATH,
                loading: false,
                roles: [],
                local: CONFIG.LANG,
                cPerms: [],
                RID: null,
                index: null,
                role: {
                    id: null,
                    name: null
                },
                permissions: [],
                keywordsPerm: null,
                cRoleName: null,
                roleSlide: 12,
                keywords: null,
                errors: []
            };
        },
        created() {
            this.getResults();
            this.getResultsPerm();
        },
        watch: {
            keywords(after, before) {
                if (this.keywords.length > 2 || this.keywords.length === 0) {
                    this.search();
                }
            },

            keywordsPerm(after, before) {
                if (this.keywordsPerm.length > 2 || this.keywordsPerm.length === 0) {
                    this.searchPerm();
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
                        "roles?page=" +
                        page +
                        "&api_token=" +
                        this.auth.api_token
                    )
                    .then(res => {
                        this.roles = res.data;
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
                        "search/roles?page=" +
                        page +
                        "&keywords=" +
                        this.keywords +
                        "&api_token=" +
                        this.auth.api_token
                    )
                    .then(res => {
                        this.roles = res.data;
                        this.loading = false;
                    });
            },
            getResultsPerm(page = 1) {
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
            searchPerm(page) {
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
                        this.keywordsPerm +
                        "&api_token=" +
                        this.auth.api_token
                    )
                    .then(res => {
                        this.permissions = res.data;
                        this.loading = false;
                    });
            },
            addEditrole(role = null) {
                this.loading = true;
                if (this.role.id) {
                    axios
                        .put(
                            CONFIG.API_URL +
                            "roles/" +
                            this.role.id +
                            "?api_token=" +
                            this.auth.api_token,
                            this.role
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
                            CONFIG.API_URL + "roles" + "?api_token=" + this.auth.api_token,
                            this.role
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
            editrole(role) {
                this.role.id = role.id;
                this.role.name = role.name;
                this.role.email = role.email;
                $("#addFeed").modal("show");
            },
            removerole(role) {
                var conf = confirm(this.local[this.lang + ".alerts"]["confirm-delete"]);
                if (conf) {
                    this.loading = false;
                    axios
                        .delete(
                            CONFIG.API_URL +
                            "roles/" +
                            role.id +
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
            expandPerm(role, index) {
                this.roleSlide = this.roleSlide == 12 ? 7 : 7;
                this.cRoleName = role.name;
                this.index = index;

                this.RID = role.id;
                this.cPerms = [];
                role.permissions.forEach(element => {
                    this.cPerms.push(element.id);
                });
            },
            togglePerm(roleID, perm) {
                this.loading = false;
                axios
                    .post(
                        CONFIG.API_URL + "toggle/permission?api_token=" + this.auth.api_token,
                        {
                            roleID: roleID,
                            perm: perm.name
                        }
                    )
                    .then(res => {
                        this.loading = false;
                        this.roles.data[this.index].permissions = res.data;
                        toastr["success"](
                            this.local[this.lang + ".alerts"]["success"],
                            this.local[this.lang + ".alerts"]["ok"]
                        );
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
            },
            clearFields() {
                this.role.name = null;
            }
        }
    };
</script>

<style scoped>
</style>
