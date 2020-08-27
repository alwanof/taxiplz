<template>
  <div>
    <div class="card">
      <div class="card-header text-center" style="background-color: #ffa500">
        <img
          class="img-thumbnail rounded-circle"
          :src="fullPath+'/storage/users/'+request.user.id+'.jpg'"
          alt="..."
          style="margin-bottom: -32px;"
        />
      </div>
      <div class="card-body text-center">
        <h5 class="card-title font-weight-bold">{{ request.user.name }}</h5>
        <table class="table" v-if="feed.data.status">
          <tr>
            <td>
              <img :src="fullPath+'/assets/dist/img/fidget-spinner.gif'" width="42" />
            </td>
            <td>
              <img
                :src="fullPath+'/assets/dist/img/analyze.gif'"
                width="42"
                v-show="[1,11,2,21,].indexOf(feed.data.status.value)>=0"
              />
              <img
                :src="fullPath+'/assets/dist/img/disappointed.gif'"
                width="42"
                v-show="[3,4].indexOf(feed.data.status.value)>=0"
              />
            </td>
            <td>
              <img
                :src="fullPath+'/assets/dist/img/check-all.gif'"
                width="42"
                v-show="[11,2,21].indexOf(feed.data.status.value)>=0"
              />
            </td>
            <td>
              <img
                :src="fullPath+'/assets/dist/img/good.gif'"
                width="42"
                v-show="[2,21].indexOf(feed.data.status.value)>=0"
              />
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <div class="progress">
                <div
                  :class="'progress-bar progress-bar-striped bg-'+status(feed.data.status.value).color"
                  role="progressbar"
                  :style="'width: '+status(feed.data.status.value).per"
                  :aria-valuenow="status(feed.data.status.value).perx"
                  aria-valuemin="0"
                  aria-valuemax="100"
                >{{ status(feed.data.status.value).text }}</div>
              </div>
            </td>
          </tr>
        </table>
        <table class="table">
          <thead>
            <tr>
              <th>Order NO.</th>
              <th>Date</th>
              <th>
                <i class="fas fa-cog fa-spin px-2 text-primary" v-show="loading">
                  <span v-html="forceRender" v-show="false"></span>
                </i>Status
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <span v-if="feed.id">#{{ feed.id.substr(feed.id.length - 4) }}</span>
              </td>
              <td>
                <span
                  v-if="feed.data.timestamp"
                  :title="feed.data.timestamp"
                >{{feed.data.timestamp | moment("from", "now")}}</span>
              </td>
              <td>
                <span
                  :class="'badge badge-'+status(feed.data.status.value).color"
                  v-if="feed.data.status"
                >
                  <i
                    class="fas fa-cog fa-spin px-2 text-white"
                    v-if="feed.data.status.value==0 || feed.data.status.value==1"
                  ></i>
                  {{status(feed.data.status.value).text}}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="feed.data.driver">
          <table class="table" v-if="feed.data.status.value==11">
            <tr class="table-success border border-danger">
              <td>
                <img
                  class="img-thumbnail rounded-circle"
                  :src="feed.data.driver.avatar"
                  alt="..."
                  width="75px"
                />
              </td>
              <td>
                {{feed.data.driver.name}}
                <br />
                <a :href="'tel:'+feed.data.driver.phone">{{feed.data.driver.phone}}</a>
              </td>
              <td>{{feed.data.driver.taxi}} {{feed.data.driver.taxiColor}}</td>
              <td>
                <button
                  type="button"
                  class="btn btn-danger"
                  :disabled="!!feed.data.enableTracking"
                  @click="trackDriver(feed)"
                >Tracking</button>
              </td>
            </tr>
          </table>
        </div>

        <div v-if="trackingmMap">
          <hr />
          <GmapMap
            ref="map"
            :center="center"
            :zoom="10"
            :draggable="true"
            style="width: 100%;height:400px"
          >
            <GmapMarker
              :clickable="true"
              :name="markers.name"
              :icon="markers.icon"
              :position="markers.position"
            >
              <gmap-info-window :position="markers.position" :opened="markers.open">{{markers.name}}</gmap-info-window>
            </GmapMarker>
            <GmapMarker
              :name="request.name"
              :icon="fullPath + '/assets/dist/img/location.png'"
              :position="center"
            >
              <gmap-info-window :position="center" :opened="true">{{request.name}}</gmap-info-window>
            </GmapMarker>
          </GmapMap>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
function objj(obj) {
  return;
}
import CONFIG from "../../app";
export default {
  name: "OrderComponent",
  props: ["request"],
  data() {
    return {
      path: CONFIG.PATH,
      fullPath: CONFIG.FULL_PATH,
      loading: false,
      feed: { id: null, data: {} },
      forceRender: 0,
      driver: 0,
      trackingmMap: false,
      center: {
        lat: 41.1374382,
        lng: 28.7547977,
      },
      points: [],
      markers: {},
    };
  },

  async created() {
    await this.geolocation();
    await this.sendOrder();

    this.getOrder();
    this.listen();
  },

  methods: {
    async getOrder() {
      this.loading = true;
      try {
        const orders = CONFIG.PARSE.Object.extend("orders");
        const query = new CONFIG.PARSE.Query(orders);
        query.containedIn("status.value", [0, 1, 11, 3, 4]);
        query.equalTo("session", this.request.session);
        query.equalTo("user.email", this.request.user.email);
        query.descending("createdAt");

        let q = await query.first();
        if (typeof q !== "undefined") {
          this.feed = { id: q.id, data: q.attributes };
        }

        this.loading = false;
      } catch (error) {
        toastr["error"]("Get Order Fail", error);
      }
    },
    async sendOrder() {
      if (!this.valid(this.request)) {
        toastr["error"]("error0", "NOT Valid!!");
        return false;
      }
      try {
        const orders = CONFIG.PARSE.Object.extend("orders");
        const query = new CONFIG.PARSE.Query(orders);
        query.containedIn("status.value", [0, 1, 11, 3]);
        query.equalTo("session", this.request.session);

        let q = await query.first();

        if (typeof q == "undefined") {
          this.loading = true;
          let order = {};
          order.session = this.request.session;
          order.enableTracking = 0;
          order.timestamp = new Date();

          order.customer = {
            name: this.request.name,
            phone: this.request.phone,
            address: this.request.address,
            location: this.center,
          };
          order.user = {
            name: this.request.user.name,
            email: this.request.user.email,
          };

          order.status = {
            timestamp: new Date(),
            value: 0,
          };
          const newOrder = new orders();
          newOrder.set(order);
          await newOrder.save();

          await this.createNoti({
            title: "New Order!",
            body: order.customer.name + " needs a taxi",
            from: order.customer,
            to: order.user,
            status: { value: 0, timestamp: new Date() },
          });

          this.loading = false;
        }
      } catch (error) {
        toastr["error"]("FAIL", error);
      }
    },
    listen() {
      const query = new CONFIG.PARSE.Query("orders");
      const driverQuery = new CONFIG.PARSE.Query("User");
      CONFIG.LIVEQ.open();
      var subscription = CONFIG.LIVEQ.subscribe(query);
      subscription.on("update", (orderDoc) => {
        query.equalTo("session", this.request.session);
        if (this.request.session == orderDoc.attributes.session) {
          this.feed = { id: orderDoc.id, data: orderDoc.attributes };
          this.forceRender = Math.random();
        }
      });
      var driverSubscription = CONFIG.LIVEQ.subscribe(driverQuery);
      driverSubscription.on("update", (driverDoc) => {
        let place = {
          lat: driverDoc.attributes.location.latitude,
          lng: driverDoc.attributes.location.longitude,
        };
        this.center = {
          lat: driverDoc.attributes.location.latitude,
          lng: driverDoc.attributes.location.longitude,
        };
        this.markers = {
          id: driverDoc.id,
          name: driverDoc.attributes.name,
          position: place,
          open: true,
          icon: this.fullPath + "/assets/dist/img/car-active.png",
        };
        this.forceRender = Math.random();
        console.log(driverDoc);
      });
    },
    createNoti(data) {
      const noti = CONFIG.PARSE.Object.extend("notifications");
      const newNoti = new noti();
      newNoti.set(data);
      newNoti.save();
    },
    trackDriver: async function (order) {
      this.trackingmMap = true;
      this.loading = true;
      const driver = CONFIG.PARSE.Object.extend("User");
      const driverQuery = new CONFIG.PARSE.Query(driver);
      driverQuery.equalTo("username", order.data.driver.username);
      let DRIVER = await driverQuery.first();
      if (typeof DRIVER !== "undefined") {
        let place = {
          lat: DRIVER.attributes.location.latitude,
          lng: DRIVER.attributes.location.longitude,
        };

        this.markers = {
          id: DRIVER.id,
          name: DRIVER.attributes.name,
          position: place,
          open: true,
          icon: this.fullPath + "/assets/dist/img/car-active.png",
        };
        /*this.directionsService = new google.maps.DirectionsService();
        this.directionsDisplay = new google.maps.DirectionsRenderer();
        this.directionsDisplay.setMap(this.$refs.map.$mapObject);
        var vm = this;
        vm.directionsService.route(
          {
            origin: this.markers.position, // Can be coord or also a search query
            destination: this.center,
            travelMode: "DRIVING",
          },
          function (response, status) {
            if (status === "OK") {
              vm.directionsDisplay.setDirections(response); // draws the polygon to the map
            } else {
              console.log("Directions request failed due to " + status);
            }
          }
        );*/
      }
      this.loading = false;
    },
    geolocation() {
      navigator.geolocation.getCurrentPosition((position) => {
        this.center = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
      });
    },

    status(state) {
      let result = { text: "", color: "" };
      switch (state) {
        case 0:
          result.text = "WAITING..";
          result.color = "warning";
          result.per = "25%";
          result.perx = "25";
          break;
        case 1:
          result.text = "PROCCESSING";
          result.color = "info";
          result.per = "50%";
          result.perx = "50";
          break;
        case 11:
          result.text = "CONFIRMED";
          result.color = "success";
          result.per = "75%";
          result.perx = "75";
          break;
        case 2:
          result.text = "DONE";
          result.color = "dark";
          result.per = "100%";
          result.perx = "100";
          break;
        case 21:
          result.text = "DONE";
          result.color = "dark";
          result.per = "100%";
          result.perx = "100";
          break;
        case 3:
          result.text = "NOT AVAILABLE";
          result.color = "danger";
          result.per = "50%";
          result.perx = "50";
          break;
        case 4:
          result.text = "NO RESPONSE";
          result.color = "danger";
          result.per = "50%";
          result.perx = "50";
          break;
        default:
          result.text = "PROCCESSED";
          result.color = "secondary";
          result.per = "100%";
          result.perx = "100";
          break;
      }

      return result;
    },
    valid(req) {
      return true;
    },
  },
};
</script>
