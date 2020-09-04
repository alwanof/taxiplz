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
              <th>{{ local[lang+".drivers"]["name"] }}</th>
              <th>{{ local[lang+".drivers"]["email"] }}</th>
              <th>{{ local[lang+".drivers"]["phone"] }}</th>
              <th>{{ local[lang+".drivers"]["taxiCode"] }}</th>
              <th>{{ local[lang+".drivers"]["taxi"] }}</th>
              <th>{{ local[lang+".drivers"]["taxiNo"] }}</th>
              <th>{{ local[lang+".drivers"]["taxiColor"] }}</th>

              <th>
                <span class="badge badge-info">{{ drivers.total}}</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr :key="driver.id" v-for="driver in drivers.data">
              <td>
                <a
                  :href="fullPath+'/admin/drivers/profile/'+driver.id"
                  target="_blank"
                >{{ driver.name}}</a>
              </td>
              <td>{{ driver.email}}</td>
              <td>{{ driver.phone}}</td>
              <td>{{ driver.taxiCode }}</td>
              <td>{{driver.taxi }}</td>
              <td>{{ driver.taxiNo }}</td>
              <td>{{ driver.taxiColor}}</td>
              <td>
                <button
                  @click="editDriver(driver)"
                  class="btn btn-sm btn-info text-white"
                  type="button"
                >
                  <i class="fa fa-edit"></i>
                </button>
                <button @click="removeDriver(driver)" class="btn btn-sm btn-danger" type="button">
                  <i class="fa fa-trash"></i>
                </button>
                <button
                  @click="activate(driver)"
                  v-if="!driver.pn"
                  class="btn btn-sm btn-warning"
                  type="button"
                >
                  <i class="fas fa-mobile-alt"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <hr />
        <div class="p-2">
          <pagination :data="Object.assign({},drivers)" @pagination-change-page="getResults"></pagination>
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
              v-show="driver.id==null"
            >{{ local[lang+".users"]["create-user"] }}</h4>
            <h4
              class="modal-title w-100 font-weight-bold"
              v-show="driver.id!=null"
            >{{ local[lang+".users"]["edit-user"] }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="addEditDriver">
            <div class="modal-body mx-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-user"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="driver.name"
                  class="form-control"
                  :placeholder="local[lang+'.drivers']['name']"
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
                  v-model="driver.email"
                  class="form-control"
                  :placeholder="local[lang+'.drivers']['email']"
                  required
                />
              </div>
              <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                  </span>
                </div>
                <input
                  type="password"
                  v-model="driver.pass"
                  class="form-control"
                  :placeholder="local[lang+'.users']['password']"
                  :required="driver.id==null"
                />
              </div>
              <div v-if="errors && errors.pass" class="text-danger">{{ errors.pass[0] }}</div>
              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-edit"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="driver.phone"
                  class="form-control"
                  :placeholder="local[lang+'.drivers']['phone']"
                  required
                />
              </div>
              <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-edit"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="driver.taxiCode"
                  class="form-control"
                  :placeholder="local[lang+'.drivers']['taxiCode']"
                  required
                />
              </div>
              <div v-if="errors && errors.taxiCode" class="text-danger">{{ errors.taxiCode[0] }}</div>
              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-edit"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="driver.taxi"
                  class="form-control"
                  :placeholder="local[lang+'.drivers']['taxi']"
                  required
                />
              </div>
              <div v-if="errors && errors.taxi" class="text-danger">{{ errors.taxi[0] }}</div>
              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-edit"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="driver.taxiNo"
                  class="form-control"
                  :placeholder="local[lang+'.drivers']['taxiNo']"
                  required
                />
              </div>
              <div v-if="errors && errors.taxiNo" class="text-danger">{{ errors.taxiNo[0] }}</div>
              <div class="input-group mt-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="far fa-edit"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="driver.taxiColor"
                  class="form-control"
                  :placeholder="local[lang+'.drivers']['taxiColor']"
                  required
                />
              </div>
              <div v-if="errors && errors.taxiColor" class="text-danger">{{ errors.taxiColor[0] }}</div>
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
  name: "DriversComponent.vue",
  props: ["auth", "lang"],
  data() {
    return {
      path: CONFIG.PATH,
      fullPath: CONFIG.FULL_PATH,
      loading: false,
      drivers: [],
      local: CONFIG.LANG,
      driver: {
        id: null,
        name: null,
        email: null,
        pass: null,
        phone: null,
        taxiCode: null,
        taxi: null,
        taxiNo: null,
        taxiColor: null,
      },
      keywords: null,
      errors: [],
      feez: [],
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
            "drivers?page=" +
            page +
            "&api_token=" +
            this.auth.api_token
        )
        .then((res) => {
          this.drivers = res.data;
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
            "search/drivers?page=" +
            page +
            "&keywords=" +
            this.keywords +
            "&api_token=" +
            this.auth.api_token
        )
        .then((res) => {
          this.drivers = res.data;
          this.loading = false;
        });
    },
    addEditDriver(driver = null) {
      this.loading = true;
      if (this.driver.id) {
        axios
          .put(
            CONFIG.API_URL +
              "drivers/" +
              this.driver.id +
              "?api_token=" +
              this.auth.api_token,
            this.driver
          )
          .then((res) => {
            this.loading = false;
            $("#addFeed").modal("hide");
            toastr["success"](
              this.local[this.lang + ".alerts"]["updated"],
              this.local[this.lang + ".alerts"]["ok"]
            );
            this.getResults(1);
            this.clearFields();
          })
          .catch((error) => {
            this.loading = false;
            if (error.response.status === 422) {
              this.errors = error.response.data.errors || {};
            } else {
              $("#addFeed").modal("hide");
              toastr["error"](
                this.local[this.lang + ".alerts"]["error"],
                error
              );
              this.clearFields();
            }
          });
      } else {
        axios
          .post(
            CONFIG.API_URL + "drivers" + "?api_token=" + this.auth.api_token,
            this.driver
          )
          .then((res) => {
            this.loading = false;
            $("#addFeed").modal("hide");
            toastr["success"](
              this.local[this.lang + ".alerts"]["added"],
              this.local[this.lang + ".alerts"]["ok"]
            );
            this.getResults(1);
            this.clearFields();
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
      }
    },
    editDriver(driver) {
      this.driver.id = driver.id;
      this.driver.name = driver.name;
      this.driver.email = driver.email;
      this.driver.phone = driver.phone;
      this.driver.taxiCode = driver.taxiCode;
      this.driver.taxi = driver.taxi;
      this.driver.taxiNo = driver.taxiNo;
      this.driver.taxiColor = driver.taxiColor;
      $("#addFeed").modal("show");
    },
    removeDriver(driver) {
      var conf = confirm(this.local[this.lang + ".alerts"]["confirm-delete"]);
      if (conf) {
        this.loading = false;
        axios
          .delete(
            CONFIG.API_URL +
              "drivers/" +
              driver.id +
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
    activate(driver) {
      this.loading = true;
      const user = new CONFIG.PARSE.User();
      user.set("name", driver.name);
      user.set("username", driver.email);
      user.set("email", driver.email);
      user.set("password", driver.pass);
      user.set("avatar", driver.avatar);
      user.set("phone", driver.phone);
      user.set("taxi", driver.taxi);
      user.set("taxiCode", driver.taxiCode);
      user.set("taxiColor", driver.taxiNo);
      user.set("taxiNo", driver.taxiNo);
      user.set("firm", { email: driver.firm });
      user.set("agent", this.auth.parent.agent);

      user.set("active", 0);

      user
        .signUp()
        .then((user) => {
          if (typeof document !== "undefined") {
            axios.post(
              CONFIG.API_URL +
                "toggle/active" +
                "?api_token=" +
                this.auth.api_token,
              { driver: driver.id, pn: user.id }
            );
            this.loading = false;
            toastr["success"](
              this.local[this.lang + ".alerts"]["updated"],
              this.local[this.lang + ".alerts"]["ok"]
            );
          }
        })
        .catch((error) => {
          if (typeof document !== "undefined") {
            this.loading = false;
            toastr["error"](this.local[this.lang + ".alerts"]["error"], error);
          }
        });
    },
    clearFields() {
      this.driver.id = this.driver.name = this.driver.email = this.driver.pass = this.driver.phone = this.driver.taxiCode = this.driver.taxi = this.driver.taxiNo = this.driver.taxiColor = null;
    },
  },
};
</script>

<style scoped>
</style>
