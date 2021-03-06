import Vue from 'vue'
import VueI18n from 'vue-i18n'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import BootstrapVue from 'bootstrap-vue'

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(BootstrapVue);

// Compoments
import App from "../components/App.vue"
import Card from "../components/Card.vue"
import BedRoomList from "../components/BedRoom-List.vue"
import BedRoom from "../components/BedRoom.vue"

// il8n config
Vue.use(VueI18n);
const i18n = new VueI18n({
    locale: document.documentElement.lang,
    messages: {
        en: {
        },
        fr: {
        }
    }
});

// routes
const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: BedRoomList
        },
        {
            path: '/:id-p-:slug',
            name: 'bedRoom',
            component: BedRoom
        }
    ]
});

const store = new Vuex.Store({
    state: {
        optionBedRoom: [],
        searchData: '',
        maxPrice : 0,
        nbPersonne : 0
    },
    getters: {
        getOptionBedRoom: state => {
            return state.optionBedRoom
        },
        getSearchData: state => {
            return state.searchData
        },
        getMaxPrice: state => {
            return state.maxPrice
        },
        getNbPersonnes: state => {
            return state.nbPersonne
        }
    },
    mutations: {
        setOptionBedRoom (state, n) {
            state.optionBedRoom = n
        },
        setSearchData (state, n) {
            state.searchData = n
        },
        setMaxPrice (state, n) {
            state.maxPrice = n
        },
        setNbPersonnes (state, n) {
            state.nbPersonne = n
        }
    }
});

// Other config
Vue.config.productionTip = false;

// Main Vue Object
new Vue({
    el: '#app',
    i18n,
    router,
    store,
    components: { App, Card }
});