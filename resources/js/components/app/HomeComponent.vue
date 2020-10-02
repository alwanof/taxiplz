<template>
  <div>
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>
              {{ metrics.inorders }}
              <small>
                {{ metrics.totalin }}
                <sup v-show="auth.level > 0">{{ currency }}</sup>
              </small>
            </h3>

            <p>Orders(in)</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>
              {{ metrics.outorders }}
              <small>
                {{ metrics.totalout }}
                <sup v-show="auth.level > 0">{{ currency }}</sup>
              </small>
            </h3>

            <p>Orders(out)</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ metrics.driverson }}</h3>

            <p>Drivers On</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ metrics.driversoff }}</h3>

            <p>Drivers Off</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div
          class="card card-primary card-outline"
          v-if="acl.access_drivers_map"
        >
          <div class="card-header">
            <button
              type="button"
              class="btn btn-danger float-right"
              @click="trackDrivers()"
            >
              Tracking
            </button>
          </div>
          <div class="card-body">
            <GmapMap
              ref="map"
              :center="center"
              :zoom="10"
              :draggable="true"
              style="width: 100%; height: 400px"
            >
              <GmapMarker
                :clickable="true"
                v-for="marker in markers"
                :key="marker.id"
                :name="marker.name"
                :icon="marker.icon"
                :position="marker.position"
              >
                <gmap-info-window :position="marker.position" :opened="true">{{
                  marker.name
                }}</gmap-info-window>
              </GmapMarker>
            </GmapMap>
          </div>
        </div>
      </div>

      <!-- hot Orders sheet -->
      <div
        class="card card-danger card-outline col-lg-12"
        v-if="acl.access_orders_sheet"
      >
        <div class="card-header">
          <i class="fas fa-cog fa-spin px-2 text-primary" v-show="loading"></i>
          <span v-html="forceRender" v-show="false"></span>
          <div class="card-tools">
            <div class="input-group input-group-sm">
              <input
                type="text"
                name="table_search"
                v-model="keywords"
                class="form-control float-right"
                :placeholder="local[lang + '.users']['search']"
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
                <th>OID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Action</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="order in hotOrders"
                :key="order.id"
                :class="'table-' + status(order.data.status.value).color"
              >
                <td>{{ order.id.substr(order.id.length - 4) }}</td>
                <td>{{ order.data.customer.name }}</td>
                <td>{{ order.data.customer.phone }}</td>
                <td>
                  <span :title="order.data.customer.address">{{
                    order.data.customer.address.substring(
                      order.data.customer.address.length - 15,
                      15
                    )
                  }}</span>
                </td>
                <td>
                  <span :title="order.data.customer.distination">{{
                    order.data.customer.distination.substring(
                      order.data.customer.distination.length - 15,
                      15
                    )
                  }}</span>
                </td>

                <td :title="order.data.status.timestamp">
                  {{ order.data.status.timestamp | moment("from", "now") }}
                </td>
                <td>
                  <span
                    v-show="[0, 12, 13].indexOf(order.data.status.value) >= 0"
                  >
                    <div class="input-group">
                      <select
                        class="form-control"
                        v-model="driver"
                        @click="driversFeed(order.data)"
                      >
                        <option value="0" disabled selected>
                          Seelct Driver
                        </option>
                        <option
                          v-for="driver in drivers"
                          :key="driver.id"
                          :value="driver.id"
                        >
                          {{ driver.data.taxiCode }} | {{ driver.data.name }}
                          {{ driver.distance }}km
                        </option>
                      </select>
                      <span class="input-group-btn">
                        <button
                          class="btn btn-warning"
                          type="button"
                          @click="pickUp(order)"
                          tabindex="-1"
                        >
                          <i
                            class="fas fa-angle-double-right"
                            v-show="!findingDrivers"
                          ></i>
                          <i
                            class="fas fa-cog fa-spin px-2 text-primary"
                            v-show="findingDrivers"
                          ></i>
                        </button>
                        <button
                          class="btn btn-danger"
                          type="button"
                          @click="reject(order)"
                          tabindex="-1"
                        >
                          <i class="fas fa-ban"></i>
                        </button>
                      </span>
                    </div>
                  </span>
                </td>
                <td>
                  <span>
                    <span
                      :class="
                        'badge badge-' + status(order.data.status.value).color
                      "
                      >{{ status(order.data.status.value).text }}</span
                    >
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <!-- /hotOrders -->

      <div
        class="card card-primary card-outline col-lg-12"
        v-if="acl.access_orders_sheet"
      >
        <div class="card-header">
          <i class="fas fa-cog fa-spin px-2 text-primary" v-show="loading"></i>
          <span v-html="forceRender" v-show="false"></span>
          <div class="card-tools">
            <div class="input-group input-group-sm">
              <input
                type="text"
                name="table_search"
                v-model="keywords"
                class="form-control float-right"
                :placeholder="local[lang + '.users']['search']"
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
                <th>OID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Action</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="order in orders"
                :key="order.id"
                :class="'table-' + status(order.data.status.value).color"
              >
                <td>{{ order.id.substr(order.id.length - 4) }}</td>
                <td>{{ order.data.customer.name }}</td>
                <td>{{ order.data.customer.phone }}</td>
                <td>
                  <span :title="order.data.customer.address">{{
                    order.data.customer.address.substring(
                      order.data.customer.address.length - 15,
                      15
                    )
                  }}</span>
                </td>
                <td>
                  <span :title="order.data.customer.distination">{{
                    order.data.customer.distination.substring(
                      order.data.customer.distination.length - 15,
                      15
                    )
                  }}</span>
                </td>
                <td :title="order.data.status.timestamp">
                  {{ order.data.status.timestamp | moment("from", "now") }}
                </td>
                <td>
                  <span
                    v-show="
                      [0, 12, 13].indexOf(order.data.status.value) >= 0 &&
                      (order.data.customer.distination == null ||
                        order.data.approved == 1)
                    "
                  >
                    <div class="input-group">
                      <select
                        class="form-control"
                        v-model="driver"
                        @click="driversFeed(order.data)"
                      >
                        <option value="0" disabled selected>
                          Seelct Driver
                        </option>
                        <option
                          v-for="driver in drivers"
                          :key="driver.id"
                          :value="driver.id"
                        >
                          {{ driver.data.taxiCode }} | {{ driver.data.name }}
                          {{ driver.distance }}km
                        </option>
                      </select>
                      <span class="input-group-btn">
                        <button
                          class="btn btn-warning"
                          type="button"
                          @click="pickUp(order)"
                          tabindex="-1"
                        >
                          <i
                            class="fas fa-angle-double-right"
                            v-show="!findingDrivers"
                          ></i>
                          <i
                            class="fas fa-cog fa-spin px-2 text-primary"
                            v-show="findingDrivers"
                          ></i>
                        </button>
                        <button
                          class="btn btn-danger"
                          type="button"
                          @click="reject(order)"
                          tabindex="-1"
                        >
                          <i class="fas fa-ban"></i>
                        </button>
                      </span>
                    </div>
                  </span>
                  <!-- offer -->
                  <span
                    v-show="
                      [0, 12, 13].indexOf(order.data.status.value) >= 0 &&
                      order.data.customer.distination != null &&
                      order.data.offer == 0
                    "
                  >
                    <div class="input-group mb-3">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Your offer.."
                        v-model="offer"
                      />
                      <div class="input-group-prepend">
                        <button
                          class="btn btn-outline-success"
                          type="button"
                          id="button-addon1"
                          @click="sendOffer(order)"
                        >
                          Send
                        </button>
                      </div>
                    </div>
                  </span>
                </td>
                <td>
                  <span>
                    <span
                      :class="
                        'badge badge-' + status(order.data.status.value).color
                      "
                      >{{ status(order.data.status.value).text }}</span
                    >
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Axios from "axios";
import CONFIG from "../../app";

export default {
  name: "HomeComponent",
  props: ["acl", "lang", "auth", "geo", "currency"],
  data() {
    return {
      path: CONFIG.PATH,
      fullPath: CONFIG.FULL_PATH,
      local: CONFIG.LANG,
      loading: false,
      findingDrivers: false,
      keywords: null,
      orders: [],
      hotOrders: [],
      drivers: [],
      driver: 0,
      offer: 0,
      metrics: {
        outorders: 0,
        inorders: 0,
        driverson: 0,
        driversoff: 0,
        totalin: 0,
        totalout: 0,
      },
      center: {
        lat: 41.1374382,
        lng: 28.7547977,
      },
      markers: [],
      forceRender: 0,
    };
  },
  async created() {
    this.listen();
    //this.geolocation();
    this.getOrdersMetrics("in");
    this.getOrdersMetrics("out");
    this.getDriverssMetrics("on");
    this.getDriverssMetrics("off");

    this.getResults();
    this.getHotResults();
  },
  watch: {
    keywords(after, before) {
      if (this.keywords.length > 2 || this.keywords.length === 0) {
        this.search();
      }
    },
  },
  methods: {
    driversFeed(order) {
      this.findingDrivers = true;
      this.drivers = [];
      //alert("hi");
      const drivers = CONFIG.PARSE.Object.extend("User");
      const query = new CONFIG.PARSE.Query(drivers);
      query.equalTo("active", 0);
      query.equalTo("firm.email", this.auth.email);

      query.find().then(
        (results) => {
          if (typeof document !== "undefined")
            results.forEach((element) => {
              if ("token" in element.attributes) {
                this.drivers.push({
                  id: element.id,
                  data: element.attributes,
                  distance: this.distance(
                    element.attributes.location._latitude,
                    element.attributes.location._longitude,
                    this.center.lat,
                    this.center.lng,
                    "k"
                  ),
                });
              }
            });
          this.sortByKey(this.drivers, "distance");
          this.findingDrivers = false;
        },
        (error) => {
          if (typeof document !== "undefined") {
            toastr["error"](this.local[this.lang + ".alerts"]["error"], error);
            this.findingDrivers = false;
          }
        }
      );
      return drivers;
    },
    getDrivers() {
      this.loading = true;
      const drivers = CONFIG.PARSE.Object.extend("User");
      const query = new CONFIG.PARSE.Query(drivers);
      query.equalTo("active", 0);
      query.equalTo("firm.email", this.auth.email);

      query.find().then(
        (results) => {
          if (typeof document !== "undefined")
            results.forEach((element) => {
              if ("token" in element.attributes) {
                this.drivers.push({ id: element.id, data: element.attributes });
              }
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
    getResults() {
      this.loading = true;
      this.orders = [];
      const orders = CONFIG.PARSE.Object.extend("orders");
      const query = new CONFIG.PARSE.Query(orders);
      query.equalTo("user.email", this.auth.email);
      query.equalTo("type", 0);
      query.notEqualTo("status.value", 5);
      query.limit(50);
      query.descending("createdAt");
      query.find().then(
        (results) => {
          if (typeof document !== "undefined")
            results.forEach((element) => {
              this.orders.push({ id: element.id, data: element.attributes });
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
    getHotResults() {
      this.loading = true;
      this.hotOrders = [];
      const orders = CONFIG.PARSE.Object.extend("orders");
      const query = new CONFIG.PARSE.Query(orders);
      query.equalTo("user.email", this.auth.email);
      query.equalTo("type", 1);
      query.notEqualTo("status.value", 5);
      query.limit(50);
      query.descending("createdAt");
      query.find().then(
        (results) => {
          if (typeof document !== "undefined")
            results.forEach((element) => {
              this.hotOrders.push({ id: element.id, data: element.attributes });
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
    listen() {
      const query = new CONFIG.PARSE.Query("orders");
      const queryDrivers = new CONFIG.PARSE.Query("User");
      CONFIG.LIVEQ.open();
      var subscriptionDrivers = CONFIG.LIVEQ.subscribe(queryDrivers);
      subscriptionDrivers.on("update", (driverDoc) => {
        let index = this.drivers.findIndex((o) => o.id === driverDoc.id);
        if (index > -1) {
          if (driverDoc.attributes.active == 1) {
            this.drivers.splice(index, 1);
          } else {
            let data = { id: driverDoc.id, data: driverDoc.attributes };
            this.drivers.splice(index, 1);
            this.drivers.unshift(data);
          }
        }
        // realtime markers
        let markerIndex = this.markers.findIndex((o) => o.id === driverDoc.id);

        if (this.markers.length == 0) {
          markerIndex = -5;
        }
        if (markerIndex > -1) {
          let place = {
            lat: driverDoc.attributes.location.latitude,
            lng: driverDoc.attributes.location.longitude,
          };

          let data = {
            id: driverDoc.id,
            name: driverDoc.attributes.name,
            position: place,
            open: true,
            icon:
              driverDoc.attributes.active == 1
                ? this.fullPath + "/assets/dist/img/car-active.png"
                : this.fullPath + "/assets/dist/img/car-deactive.png",
          };

          this.markers[markerIndex] = data;
        }
        // end // realtime markers

        this.forceRender = Math.random();
      });

      var subscription = CONFIG.LIVEQ.subscribe(query);

      subscription.on("create", (orderDoc) => {
        if (orderDoc.attributes.user) {
          if (orderDoc.attributes.user.email == this.auth.email) {
            let data = { id: orderDoc.id, data: orderDoc.attributes };
            this.orders.unshift(data);
          }
        } else {
          let range = this.distance(
            this.geo.lng,
            this.geo.lat,
            orderDoc.get("customer").location.lat,
            orderDoc.get("customer").location.lng,
            "km"
          );
          if (range < 100) {
            let data = { id: orderDoc.id, data: orderDoc.attributes };
            this.hotOrders.unshift(data);
          }
        }
      });
      subscription.on("update", (orderDoc) => {
        let index = this.orders.findIndex((o) => o.id === orderDoc.id);
        let data = { id: orderDoc.id, data: orderDoc.attributes };
        this.orders[index] = data;

        let index1 = this.hotOrders.findIndex((o) => o.id === orderDoc.id);
        let data1 = { id: orderDoc.id, data: orderDoc.attributes };
        if (orderDoc.attributes.user) {
          if (orderDoc.attributes.user.email == this.auth.email) {
            this.hotOrders[index1] = data1;
          } else {
            this.hotOrders.splice(index1, 1);
          }
        }

        this.forceRender = Math.random();
      });
      subscription.on("delete", (orderDoc) => {
        let index = this.orders.findIndex((o) => o.id === orderDoc.id);
        this.orders.splice(index, 1);

        let index1 = this.hotOrders.findIndex((o) => o.id === orderDoc.id);
        this.hotOrders.splice(index1, 1);

        this.forceRender = Math.random();
      });
    },

    async getOrdersMetrics(metric) {
      const orders = CONFIG.PARSE.Object.extend("orders");
      const query = new CONFIG.PARSE.Query(orders);
      if (metric == "in") {
        query.equalTo("type", 0);
      } else {
        query.equalTo("type", 1);
      }
      switch (this.auth.level) {
        case 2:
          query.equalTo("user.email", this.auth.email);
          break;
        case 1:
          query.equalTo("agent", this.auth.email);
          break;
        case 0:
          break;

        default:
          query.equalTo("user.email", "foo@foo.com");
          break;
      }

      let ordersData = await query.find();
      let totalOffers = 0;
      ordersData.forEach((item) => {
        totalOffers = totalOffers + item.attributes.offer;
      });

      if (metric == "in") {
        this.metrics.inorders = ordersData.length;
        this.metrics.totalin = totalOffers;
      } else {
        this.metrics.outorders = ordersData.length;
        this.metrics.totalout = totalOffers;
      }
    },
    async getDriverssMetrics(metric) {
      const users = CONFIG.PARSE.Object.extend("User");
      const query = new CONFIG.PARSE.Query(users);
      if (metric == "on") {
        query.equalTo("active", 1);
      } else {
        query.equalTo("active", 0);
      }
      switch (this.auth.level) {
        case 2:
          query.equalTo("firm.email", this.auth.email);
          break;
        case 1:
          query.equalTo("agent.email", this.auth.email);
          break;
        case 0:
          break;

        default:
          query.equalTo("firm.email", "foo@foo.com");
          break;
      }

      let ordersData = await query.find();

      if (metric == "on") {
        this.metrics.driverson = ordersData.length;
      } else {
        this.metrics.driversoff = ordersData.length;
      }
    },
    pickUp: async function (order) {
      this.loading = true;

      let index = this.drivers.findIndex((o) => o.id === this.driver);
      const orders = CONFIG.PARSE.Object.extend("orders");
      const query = new CONFIG.PARSE.Query(orders);

      await query.get(order.id).then((object) => {
        object.set("status", { timestamp: new Date(), value: 1 });
        object.set("driver", this.drivers[index].data);
        if (!order.user) {
          object.set("user", {
            name: this.auth.name,
            email: this.auth.email,
            avatar: this.auth.avatar,
          });
          object.set("agent", this.auth.parent.agent.email);
        }
        object.save();
      });
      await axios
        .post(
          "https://fcm.googleapis.com/fcm/send",
          {
            to: this.drivers[index].data.token,
            notification: {
              title: "New " + this.auth.name + " Order's",
              body: order.data.customer.name + " needs a taxi",
            },
          },
          {
            headers: {
              "content-type": "application/json",
              Authorization:
                "key=AAAAAM6DuQo:APA91bG88dJ6IZ2GULlgR-goGhxI9cVaKRB3x9GT1CQ8R52z_rkBalZqohnCWNC_lD0yE2fPG5nB83HQTqFVVihKsvv4i-9ZPgQzPlKWxgVwOTmr4PX6gNvoxdK3fmnYkM1KOi9V-i28",
            },
          }
        )
        .then(function (response) {
          console.log(response);
        })
        .catch(function (error) {
          console.log(error);
        });

      this.loading = false;
    },
    async reject(order) {
      this.loading = true;
      //let index = this.drivers.findIndex((o) => o.id === this.driver);
      const orders = CONFIG.PARSE.Object.extend("orders");
      const query = new CONFIG.PARSE.Query(orders);
      await query.get(order.id).then((object) => {
        object.set("status", { timestamp: new Date(), value: 3 });
        object.save();
      });
      this.loading = false;
    },
    async sendOffer(order) {
      this.loading = true;
      const orders = CONFIG.PARSE.Object.extend("orders");
      const query = new CONFIG.PARSE.Query(orders);
      await query.get(order.id).then((object) => {
        object.set("offer", Number(this.offer));
        object.save();
      });
      this.loading = false;
      this.offer = 0;
    },
    search() {},
    trackDrivers: async function () {
      this.loading = true;
      await this.geolocation();
      this.markers = [];
      const driver = CONFIG.PARSE.Object.extend("User");
      const driverQuery = new CONFIG.PARSE.Query(driver);
      switch (this.auth.level) {
        case 2:
          driverQuery.equalTo("firm.email", this.auth.email);
          break;
        case 1:
          driverQuery.equalTo("agent", this.auth.email);
          break;
      }

      let DRIVERS = await driverQuery.find();
      if (typeof document !== "undefined") {
        DRIVERS.forEach((DRIVER) => {
          if (typeof DRIVER.attributes.location !== "undefined") {
            let place = {
              lat: DRIVER.attributes.location.latitude,
              lng: DRIVER.attributes.location.longitude,
            };

            this.markers.push({
              id: DRIVER.id,
              name: DRIVER.attributes.name,
              position: place,
              open: true,
              icon:
                DRIVER.attributes.active == 1
                  ? this.fullPath + "/assets/dist/img/car-active.png"
                  : this.fullPath + "/assets/dist/img/car-deactive.png",
            });
          }
        });
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
    distance(lat1, lon1, lat2, lon2, unit) {
      if (lat1 == lat2 && lon1 == lon2) {
        return 0;
      } else {
        var radlat1 = (Math.PI * lat1) / 180;
        var radlat2 = (Math.PI * lat2) / 180;
        var theta = lon1 - lon2;
        var radtheta = (Math.PI * theta) / 180;
        var dist =
          Math.sin(radlat1) * Math.sin(radlat2) +
          Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        if (dist > 1) {
          dist = 1;
        }
        dist = Math.acos(dist);
        dist = (dist * 180) / Math.PI;
        dist = dist * 60 * 1.1515;
        if (unit == "K") {
          dist = dist * 1.609344;
        }
        if (unit == "N") {
          dist = dist * 0.8684;
        }
        return dist.toFixed(1);
      }
    },
    sortByKey(array, key) {
      return array.sort(function (a, b) {
        var x = a[key];
        var y = b[key];
        return x < y ? -1 : x > y ? 1 : 0;
      });
    },
    status(state) {
      let result = { text: "", color: "" };
      switch (state) {
        case 0:
          result.text = "WAITING..";
          result.color = "warning";
          break;
        case 1:
          result.text = "FORWARDED";
          result.color = "warning";
          break;
        case 11:
          result.text = "CONFIRMED";
          result.color = "success";
          break;
        case 12:
          result.text = "REJECTED FROM DRIVER";
          result.color = "danger";
          break;
        case 13:
          result.text = "NO RESPONSE FROM DRIVER";
          result.color = "info";
          break;
        case 2:
          result.text = "DONE";
          result.color = "secondary";
          break;
        case 21:
          result.text = "DONE BY SYSTEM";
          result.color = "dark";
          break;
        case 3:
          result.text = "REJECTED";
          result.color = "danger";
          break;
        case 4:
          result.text = "NO RESPONSE";
          result.color = "info";
          break;
        default:
          result.text = "Error!!";
          result.color = "danger";
          break;
      }

      return result;
    },
  },
};
</script>
