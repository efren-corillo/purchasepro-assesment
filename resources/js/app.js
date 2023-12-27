import './bootstrap'
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import '../css/app.scss'

import App from './App.vue'
import router from './router'

import 'sweetalert2/dist/sweetalert2.min.css'
import VueSweetalert2 from 'vue-sweetalert2'

const app = createApp( App )

app.use( createPinia() )
app.use( router )
app.use( VueSweetalert2 )
app.mount( '#q-app' )