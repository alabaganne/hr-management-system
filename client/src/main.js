import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import 'bootstrap/dist/css/bootstrap.min.css'
// Bootstrap JS removed - using Vue components instead

// axios with configured interceptors
import axios from './plugins/axios'
window.axios = axios

// Make base URL globally available
window.BASE_URL = import.meta.env.VITE_BASE_URL || 'http://localhost:8000'

// Create Pinia store
const pinia = createPinia()

// Create Vue app
const app = createApp(App)

// Global filter replacement (Vue 3 removed filters, use global properties instead)
app.config.globalProperties.$filters = {
  clean(value) {
    if (!value) return ''
    value = value.toString() && value.replace('_', ' ')
    return value.charAt(0).toUpperCase() + value.slice(1)
  }
}

// Use plugins
app.use(pinia)
app.use(router)

// Re-authenticate the user if a valid token is present in localStorage
const token = localStorage.getItem('token')

// Import auth store
import { useAuthStore } from './store/auth'

// Attempt to authenticate, then mount the app
if (token) {
  const authStore = useAuthStore(pinia)
  authStore.attempt(token)
    .catch(error => {
      console.log(error.response)
    })
    .finally(() => {
      app.mount('#app')
    })
} else {
  app.mount('#app')
}
