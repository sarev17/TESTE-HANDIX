import '../css/app.css';
import './bootstrap';
import { createApp } from 'vue';
import { vMaska } from "maska/vue";
import App from './App.vue';

// Configuração do Toast
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const app = createApp(App);
app.use(Toast, {
    position: "top-right",
    timeout: 4000,
    closeOnClick: true,
    pauseOnHover: true,
});
app.directive("maska", vMaska);
app.mount('#app');