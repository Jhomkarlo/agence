require('./bootstrap');

import Vuetify from 'vuetify';
import currency from 'currency.js';

import 'vuetify/dist/vuetify.min.css';
import { DateTime } from 'luxon';

window.Vue = require('vue');

Vue.use(Vuetify);

const R = value => currency(value, {symbol: "R$", separator: '.', decimal: ',', precision: 2, pattern: '! #', negativePattern: '-! #'});
const floatAmmount = value => parseFloat(value ?? 0);

Vue.mixin({
    data() {
        return {
            formatterComponent: {
                asFloat: floatAmmount,
                asCurrencyReal: R,
                asRealFormatted: value => R(floatAmmount(value)).format(),
                dateFormatted: unix => DateTime.fromSeconds(unix).toFormat('dd/MM/yyyy'),
                dateFromSeconds: unix => DateTime.fromSeconds(unix),
            },
            stringHelper: {
                capitalize: s => s.charAt(0).toUpperCase() + s.slice(1)
            },
        };
    },
});

Vue.component('agence-index', require('./components/agence/index.vue').default);
Vue.component('a-nav', require('./components/Nav.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});