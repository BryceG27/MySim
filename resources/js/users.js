import {createApp} from 'vue';
import App from './components/App.vue';
import UsersView from './components/UsersView.vue';

const options = {};

const app = createApp(options);

app.component('App', App);
app.component('UsersView', UsersView);
app.mount('#users');