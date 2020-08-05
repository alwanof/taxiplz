<template>
  <div>
    <div class="row">
      <div :class="'card card-primary card-outline col-lg-'+roleSlide">
        <div class="card-header">
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addFeed">
            <i class="fas fa-plus"></i>
          </button>
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
        <div class="card-body p-0">
          <table class="table table-striped table-hover table-head-fixed">
            <thead>
              <tr>
                <th>{{ local[lang+".config"]["name"] }}</th>
                <th>{{ local[lang+".config"]["value"] }}</th>
                <th>
                  <span class="badge badge-info">{{ configs.total}}</span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(config,index) in configs.data" :key="config.id">
                <td>{{ config.name }}</td>
                <td>{{ config.value }}</td>
                <td>
                  <button
                    type="button"
                    class="btn btn-sm btn-info text-white"
                    @click="editConfig(config)"
                  >
                    <i class="fa fa-edit"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-danger" @click="removeConfig(config)">
                    <i class="fa fa-trash"></i>
                  </button>
                  <button
                    type="button"
                    class="btn btn-sm btn-warning"
                    @click="expandRoles(config,index)"
                  >
                    <i class="fa fa-eye"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <hr />
          <div class="p-2">
            <pagination :data="Object.assign({},configs)" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>
      <div
        :class="'card card-warning card-outline mx-1 col-lg-'+(11-roleSlide)"
        v-show="roleSlide==7"
      >
        <div class="card-header">{{ local[lang+".config"]["roles"] }} / {{ config.name }}</div>
        <div class="card-body p-0">
          <ul class="list-group">
            <li class="list-group-item" v-for="role in roles" :key="role.id">
              <div
                class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success"
              >
                <input
                  type="checkbox"
                  class="custom-control-input"
                  :id="'switch'+role.name"
                  v-model="cRoles"
                  v-bind:value="role.id"
                  @click="toggleRole(CID,role)"
                />
                <label class="custom-control-label" :for="'switch'+role.name">{{ role.name }}</label>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- The Modal -->
    <div
      class="modal fade"
      id="addFeed"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h4
              class="modal-title w-100 font-weight-bold"
              v-show="config.id==null"
            >{{ local[lang+".config"]["create-config"] }}</h4>
            <h4
              class="modal-title w-100 font-weight-bold"
              v-show="config.id!=null"
            >{{ local[lang+".config"]["edit-config"] }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="addEditConfig">
            <div class="modal-body mx-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-cog"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="config.name"
                  class="form-control"
                  :placeholder="local[lang+'.config']['name']"
                  required
                />
              </div>
              <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>

              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-user-cog"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="config.value"
                  class="form-control"
                  :placeholder="local[lang+'.config']['value']"
                  required
                />
              </div>
              <div v-if="errors && errors.value" class="text-danger">{{ errors.value[0] }}</div>
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
import CONFIG from "../app";

export default {
  name: "ConfigComponent.vue",
  props: ["auth", "lang", "roles"],
  data() {
    return {
      path: CONFIG.PATH,
      loading: false,
      roleSlide: 12,
      configs: [],
      local: CONFIG.LANG,
      cRoles: [],
      index: null,
      CID: null,
      config: {
        id: null,
        name: null,
        value: null
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
            "configs?page=" +
            page +
            "&api_token=" +
            this.auth.api_token
        )
        .then(res => {
          this.configs = res.data;
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
            "search/configs?page=" +
            page +
            "&keywords=" +
            this.keywords +
            "&api_token=" +
            this.auth.api_token
        )
        .then(res => {
          this.configs = res.data;
          this.loading = false;
        });
    },
    addEditConfig(config = null) {
      this.loading = true;
      if (this.config.id) {
        axios
          .put(
            CONFIG.API_URL +
              "configs/" +
              this.config.id +
              "?api_token=" +
              this.auth.api_token,
            this.config
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
            CONFIG.API_URL + "configs" + "?api_token=" + this.auth.api_token,
            this.config
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
    editConfig(config) {
      this.config.id = config.id;
      this.config.name = config.name;
      this.config.value = config.value;
      $("#addFeed").modal("show");
    },
    removeConfig(config) {
      var conf = confirm(this.local[this.lang + ".alerts"]["confirm-delete"]);
      if (conf) {
        this.loading = false;
        axios
          .delete(
            CONFIG.API_URL +
              "configs/" +
              config.id +
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
    expandRoles(config, index) {
      this.index = index;
      this.roleSlide = this.roleSlide == 12 ? 7 : 7;
      this.CID = config.id;
      this.config.name = config.name;
      this.cRoles = [];
      config.roles.forEach(element => {
        this.cRoles.push(element.id);
      });
    },
    toggleRole(configID, role) {
      this.loading = false;
      //this.cRoles = [];

      axios
        .post(
          CONFIG.API_URL +
            "toggle/config/role?api_token=" +
            this.auth.api_token,
          {
            configID: configID,
            role: role.id
          }
        )
        .then(res => {
          this.loading = false;
          this.configs.data[this.index].roles = res.data.data;

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
      this.config.name=this.config.id = this.config.value = null;
    }
  }
};
</script>

<style scoped>
</style>
