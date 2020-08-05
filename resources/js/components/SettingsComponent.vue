<template>
  <div>
    <div class="card card-primary card-outline">
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
      <div class="card-body p-0">
        <table class="table table-striped table-hover table-head-fixed">
          <thead>
            <tr>
              <th>{{ local[lang+".config"]["name"] }}</th>
              <th>{{ local[lang+".config"]["value"] }}</th>
              <th>
                <span class="badge badge-info">{{ settings.total}}</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="setting in settings.data" :key="setting.id">
              <td>{{ setting.name }}</td>
              <td>{{ setting.value}}</td>
              <td>
                <button
                  type="button"
                  class="btn btn-sm btn-info text-white"
                  @click="editSetting(setting)"
                >
                  <i class="fa fa-edit"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <hr />
        <div class="p-2">
          <pagination :data="Object.assign({},settings)" @pagination-change-page="getResults"></pagination>
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
            >{{ local[lang+".config"]["edit-setting"] }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="addEditSetting">
            <div class="modal-body mx-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="fas fa-cog"></i>
                  </span>
                </div>
                <input
                  type="text"
                  v-model="setting.name"
                  class="form-control"
                  :placeholder="local[lang+'.config']['name']"
                  required
                  disabled
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
                  v-model="setting.value"
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
  name: "SettingsComponent.vue",
  props: ["auth", "lang"],
  data() {
    return {
      path: CONFIG.PATH,
      loading: false,
      settings: [],
      local: CONFIG.LANG,
      setting: {
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
            "settings?page=" +
            page +
            "&api_token=" +
            this.auth.api_token
        )
        .then(res => {
          this.settings = res.data;
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
            "search/settings?page=" +
            page +
            "&keywords=" +
            this.keywords +
            "&api_token=" +
            this.auth.api_token
        )
        .then(res => {
          this.settings = res.data;
          this.loading = false;
        });
    },
    addEditSetting(setting = null) {
      this.loading = true;
      axios
        .put(
          CONFIG.API_URL +
            "settings/" +
            this.setting.id +
            "?api_token=" +
            this.auth.api_token,
          this.setting
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
    },
    editSetting(setting) {
      this.setting.id = setting.id;
      this.setting.name = setting.name;
      this.setting.value = setting.value;
      $("#addFeed").modal("show");
    },
    clearFields() {
      this.setting.name = this.setting.value = null;
    }
  }
};
</script>

<style scoped>
</style>
