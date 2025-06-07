import { createApp } from 'vue'
import { createPinia } from 'pinia'

import './css/main.css'
import App from './App.vue'
import router from './router'

const pania = createPinia()

createApp(App).use(pania).use(router).mount('#app')
