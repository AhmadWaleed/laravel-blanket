import { createApp } from 'vue'
import './tailwind.css'
import App from './App.vue'
import lodash from 'lodash'
import config from './config'
import { createPinia } from 'pinia'
import 'boxicons/css/boxicons.min.css'
import Divider from './components/Divider'
import Loading from './components/Loading'

window.__ = lodash

const app = createApp(App)
app.component('Divider', Divider)
app.component('Loading', Loading)
app.use(createPinia())
app.config.globalProperties.config = config
app.mount('#app')
