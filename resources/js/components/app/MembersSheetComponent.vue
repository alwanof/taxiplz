<template>
  <div>
    <h1>
      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addFeed">
        <i class="fas fa-plus"></i>
      </button>
      {{local[lang+'.members']['title']}}
    </h1>
    <div class="row">
      <div class="card card-primary card-outline col-12">
        <div class="card-header">
          <i class="fas fa-cog fa-spin px-2 text-primary" v-show="loading"></i>
          <div class="card-tools">
            <div class="input-group input-group-sm">
              <input
                type="text"
                name="table_search"
                v-model="keywords"
                class="form-control float-right"
                :placeholder=" local[lang+'.users']['search'] "
              />

              <div class="input-group-append">
                <button type="submit" class="btn btn-default">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body p-0 table-responsive">
          <table class="table table-striped table-hover table-head-fixed">
            <thead>
              <tr>
                <th>{{ local[lang+".members"]["name"] }}</th>
                <th>{{ local[lang+".members"]["email"] }}</th>
                <th>{{ local[lang+".members"]["level"] }}</th>
                <th>{{ local[lang+".members"]["parent"] }}</th>
                <th>
                  <span class="badge badge-info">{{members.total}}</span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(member,index) in members.data" :key="member.id">
                <td>
                  <img
                    :src="member.avatar+'?r='+Math.random()"
                    class="rounded-circle mx-1"
                    width="24px"
                  />
                  {{ member.name }}
                </td>
                <td>{{member.email}}</td>
                <td>{{level(Number(member.level))}}</td>
                <td>
                  <div v-if="member.refuser">
                    <img
                      :src="member.refuser.avatar+'?r='+Math.random()"
                      class="rounded-circle mx-1"
                      width="24px"
                    />
                    {{member.refuser.name}}
                    <span
                      class="badge badge-info"
                    >{{level(Number(member.refuser.level))}}</span>
                  </div>
                </td>
                <td>
                  <button type="button" class="btn btn-sm btn-info mx-1" @click="editUser(member)">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-dark mx-1">
                    <i class="fas fa-power-off"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-danger mx-1">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <hr />
          <div class="p-2">
            <pagination :data="Object.assign({},members)" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="addFeed" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h4
              class="modal-title w-100 font-weight-bold"
              v-show="member.id==null"
            >{{ local[lang+".users"]["create-user"] }}</h4>
            <h4
              class="modal-title w-100 font-weight-bold"
              v-show="member.id!=null"
            >{{ local[lang+".users"]["edit-user"] }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="addEditUser">
            <div class="modal-body mx-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-user"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="member.name"
                  class="form-control"
                  :placeholder="local[lang+'.users']['name']"
                  required
                />
              </div>
              <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>

              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                  </span>
                </div>
                <input
                  type="email"
                  v-model="member.email"
                  class="form-control"
                  :placeholder="local[lang+'.users']['email']"
                  required
                />
              </div>
              <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>

              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-user-cog"></i>
                  </span>
                </div>
                <select class="form-control" v-model="member.level" required>
                  <option value="99" selected disabled>{{local[lang+".members"]["select_role"]}}</option>
                  <option
                    v-for="(element,index) in levels"
                    :key="index"
                    :value="element"
                  >{{level(element)}}</option>
                </select>
              </div>
              <div v-if="errors && errors.level" class="text-danger">{{ errors.level[0] }}</div>

              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                  </span>
                </div>
                <input
                  type="password"
                  v-model="member.password"
                  class="form-control"
                  :placeholder="local[lang+'.users']['password']"
                  :required="member.id==null"
                />
              </div>
              <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
              <button type="submit" class="btn btn-success">
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
import CONFIG from "../../app";

export default {
  name: "MemberSheetComponent",
  props: ["auth", "lang", "levels"],
  data() {
    return {
      path: CONFIG.PATH,
      loading: false,
      members: [],
      local: CONFIG.LANG,
      member: {
        id: null,
        name: null,
        email: null,
        password: null,
        level: 99,
      },
      keywords: null,
      errors: [],
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
    },
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
            "members?page=" +
            page +
            "&api_token=" +
            this.auth.api_token
        )
        .then((res) => {
          this.members = res.data;
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
            "search/members?page=" +
            page +
            "&keywords=" +
            this.keywords +
            "&api_token=" +
            this.auth.api_token
        )
        .then((res) => {
          this.members = res.data;
          this.loading = false;
        });
    },
    addEditUser(member = null) {
      this.loading = true;
      if (this.member.id) {
        axios
          .put(
            CONFIG.API_URL +
              "members/" +
              this.member.id +
              "?api_token=" +
              this.auth.api_token,
            this.member
          )
          .then((res) => {
            this.loading = false;
            $("#addFeed").modal("hide");
            toastr["success"](
              this.local[this.lang + ".alerts"]["updated"],
              this.local[this.lang + ".alerts"]["ok"]
            );
            this.clearFields();
            this.getResults(1);
          })
          .catch((error) => {
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
            CONFIG.API_URL + "members" + "?api_token=" + this.auth.api_token,
            this.member
          )
          .then((res) => {
            this.loading = false;
            $("#addFeed").modal("hide");
            toastr["success"](
              this.local[this.lang + ".alerts"]["added"],
              this.local[this.lang + ".alerts"]["ok"]
            );
            this.clearFields();
            this.getResults(1);
          })
          .catch((error) => {
            console.log(error);
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
    editUser(member) {
      this.member.id = member.id;
      this.member.name = member.name;
      this.member.email = member.email;
      this.member.level = member.level;
      $("#addFeed").modal("show");
    },
    removeMember(member) {
      var conf = confirm(this.local[this.lang + ".alerts"]["confirm-delete"]);
      if (conf) {
        this.loading = false;
        axios
          .delete(
            CONFIG.API_URL +
              "members/" +
              member.id +
              "?api_token=" +
              this.auth.api_token
          )
          .then((res) => {
            this.loading = false;
            toastr["success"](
              this.local[this.lang + ".alerts"]["removed"],
              this.local[this.lang + ".alerts"]["ok"]
            );
            this.getResults(1);
          })
          .catch((error) => {
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
    level(value) {
      switch (value) {
        case 0:
          return "root";
          break;
        case 1:
          return "agent";
          break;
        case 2:
          return "office";
          break;
      }
      return false;
    },
    clearFields() {
      this.member.name = this.member.email = this.password = null;
      this.member.level = 99;
    },
  },
};
</script>

<style scoped>
</style>
