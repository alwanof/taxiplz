<template>
  <div class="row">
    <div class="col-lg-9">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <button
            class="btn btn-success"
            data-target="#addFeed"
            data-toggle="modal"
            type="button"
          >
            <i class="fas fa-plus"></i>
          </button>
          <i class="fas fa-cog fa-spin px-2 text-primary" v-show="loading"></i>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped table-hover table-head-fixed">
            <thead>
              <tr>
                <th>
                  {{ local[lang + ".drivers"]["history"]["customer_name"] }}
                </th>
                <th>{{ local[lang + ".drivers"]["history"]["from"] }}</th>
                <th>{{ local[lang + ".drivers"]["history"]["to"] }}</th>
                <th>{{ local[lang + ".drivers"]["history"]["date"] }}</th>
                <th>{{ local[lang + ".drivers"]["history"]["office"] }}</th>
                <th>{{ local[lang + ".drivers"]["history"]["agent"] }}</th>
                <th>{{ local[lang + ".drivers"]["history"]["driver"] }}</th>
                <th>{{ local[lang + ".drivers"]["history"]["type"] }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="feed in feeds" :key="feed.id">
                <td>{{ feed.data.customer.name }}</td>
                <td>{{ feed.data.customer.address }}</td>
                <td>{{ feed.data.customer.distination }}</td>
                <td>{{ feed.data.timestamp | moment("from", "now") }}</td>
                <td>{{ feed.data.user.name }}</td>
                <td>{{ feed.data.agent }}</td>
                <td>{{ feed.data.driver ? feed.data.driver.name : "" }}</td>
                <td>{{ feed.data.type }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-3"></div>
  </div>
</template>

<script>
import CONFIG from "../../app";

export default {
  name: "HistoryComponent.vue",
  props: ["auth", "lang"],
  data() {
    return {
      path: CONFIG.PATH,
      fullPath: CONFIG.FULL_PATH,
      loading: false,
      feeds: [],
      local: CONFIG.LANG,
      errors: [],
    };
  },
  created() {
    this.getResults();
  },

  methods: {
    getResults() {
      this.loading = true;
      const orders = CONFIG.PARSE.Object.extend("orders");
      const query = new CONFIG.PARSE.Query(orders);
      if (this.auth.level == 2) {
        query.equalTo("user.email", this.auth.email);
      } else if (this.auth.level == 1) {
        query.equalTo("agent", this.auth.email);
      }
      query.limit(100);

      query.find().then(
        (results) => {
          if (typeof document !== "undefined")
            results.forEach((element) => {
              this.feeds.push({ id: element.id, data: element.attributes });
            });
          this.loading = false;
        },
        (error) => {
          if (typeof document !== "undefined") {
            toastr["error"](this.local[this.lang + ".alerts"]["error"], error);
            this.loading = false;
          }
        }
      );
    },
  },
};
</script>

<style scoped>
</style>
