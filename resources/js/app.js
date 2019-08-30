/**
 * First we will load all of this project"s JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import InstantSearch from "vue-instantsearch";
import VModal from "vue-js-modal"

window.Vue = require("vue");

Vue.use(InstantSearch);
Vue.use(VModal);

Vue.config.ignoredElements = ["trix-editor"];

let authorizations = require("./authorizations");

// # Used to allow authorize in Vue
// # ...params takes multiple types of formats either
// # authorize(() => {})   or authorize("foo", "bar")
Vue.prototype.authorize = function (...params) {
    // Additional admin privileges.
    // # if they not sign in then not authorized
    if (! window.App.signedIn) return false;

    if (typeof params[0] === "string") {
       return authorizations[params[0]](params[1]);
    }

    return params[0](window.App.user);
    // # if you not sign it it returns false
    // # otherwise it triggers callback function and pass in the user.
    // return user ? handler(user) : false;
};

Vue.prototype.signedIn = window.App.signedIn;


window.events = new Vue();

// # sends event to Flash.vue

// # success is the boostrap color for flash message as default.
window.flash = function (message, level = "success") {
    window.events.$emit("flash", { message, level });
};// flash("my new flash message")

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/Flash.vue -> <example-component></example-component>
 */

// const files = require.context("./", true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split("/").pop().split(".")[0], files(key).default));

Vue.component("flash", require("./components/Flash.vue").default);

Vue.component("login", require("./components/Login.vue").default);

Vue.component("dropdown", require("./components/Dropdown.vue").default);

Vue.component("logout-button", require("./components/LogoutButton.vue").default);

// Vue.component("logout-button", require("./components/LogoutButton.vue").default);

Vue.component("register", require("./components/Register.vue").default);

Vue.component("paginator", require("./components/Paginator.vue").default);

Vue.component("user-notifications", require("./components/UserNotifications.vue").default);

Vue.component("avatar-form", require("./components/AvatarForm.vue").default);

Vue.component("thread-view", require("./pages/Thread.vue").default);

Vue.component("wysiwyg", require("./components/Wysiwyg.vue").default);

Vue.component("channel-dropdown", require("./components/ChannelDropdown.vue").default);

// Vue.component("wysiwyg-vue", require("./components/WysiwygVue.vue").default);

// Vue.component("editor-vue", require("./components/EditorVue.vue").default);

Vue.component("scan", require("./components/Scan.vue").default);

Vue.component("media", require("./components/Media.vue").default);
// Vue.component("reply", require("./components/Reply.vue").default);
// Vue.component("favorite", require("./components/Favorite.vue").default);
// Vue.component("replies", require("./components/Replies.vue").default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",

    data: {
        searching: false
    },

    methods: {
        search() {
            this.searching = true;

            this.$nextTick(() => {
                this.$refs.search.focus();
            });
        }
    }
});
