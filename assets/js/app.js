import Vue from 'vue';
import axios from 'axios';

/** Inicializar Axios */
Vue.prototype.$http = axios;

/** Componentes */
Vue.component('o-contacts', require('./components/contacts.vue'));
Vue.component('o-contact', require('./components/contact.vue'));
Vue.component('o-contact-view', require('./components/contact-view.vue'));
Vue.component('o-contact-form', require('./components/contact-form.vue'));
Vue.component('o-contact-menu', require('./components/contact-menu.vue'));
Vue.component('o-avatar', require('./components/contact-avatar.vue'));

/** Inicialização do Vue.js */
window.app = new Vue({ el: '#app' });