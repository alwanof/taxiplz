<template>
  <div>
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">
          <i class="fas fa-cog fa-spin" v-show="loading"></i>
          {{ feeds.length }}
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">{{ feeds.length }} Notifications</span>
        <div class="dropdown-divider"></div>
        <div v-for="feed in feeds" :key="feed.id">
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i>
            {{ feed.get('title') }}
            <span
              class="float-right text-muted text-sm"
            >{{ feed.createdAt | moment("from", "now") }}</span>
          </a>
          <div class="dropdown-divider"></div>
        </div>
      </div>
    </li>
  </div>
</template>

<script>
import CONFIG from "../../app";

export default {
  name: "NotificationComponent",
  props: ["auth"],
  data() {
    return {
      path: CONFIG.PATH,
      fullPath: CONFIG.FULL_PATH,
      loading: false,
      feeds: [],
    };
  },
  async created() {
    await this.getFeeds();
    await this.listen();
  },
  methods: {
    async getFeeds() {
      this.loading = true;
      try {
        const notifications = CONFIG.PARSE.Object.extend("notifications");
        const query = new CONFIG.PARSE.Query(notifications);
        query.equalTo("to.email", this.auth.email);
        query.limit(10);
        query.descending("createdAt");

        let q = await query.find();
        if (typeof q !== "undefined") {
          this.feeds = q;
        }

        this.loading = false;
      } catch (error) {
        toastr["error"]("Get Order Fail", error);
      }
    },
    listen() {
      const query = new CONFIG.PARSE.Query("notifications");
      //CONFIG.LIVEQ.open();
      var subscription = CONFIG.LIVEQ.subscribe(query);
      subscription.on("create", (notiDoc) => {
        if (notiDoc.get("to")) {
          if (notiDoc.get("to").email == this.auth.email) {
            console.log(notiDoc.get("to").email);
            this.feeds.unshift(notiDoc);
            this.notify({
              title: notiDoc.get("title"),
              body: notiDoc.get("body"),
            });
          }
        }
      });
    },
    notify(data) {
      // https://developer.mozilla.org/en-US/docs/Web/API/Notification/Notification#Parameters
      this.$notification.show(
        data.title,
        {
          body: data.body,
        },
        {}
      );
    },
  },
};
</script>
