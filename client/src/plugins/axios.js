import axios from 'axios'
import { useAuthStore } from '@/store/auth'
import { useNotificationStore } from '@/store/notification'
import router from '@/router'

// Configure axios defaults
axios.defaults.baseURL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'

// Track if we're currently refreshing the token to prevent multiple refresh requests
let isRefreshing = false
let failedQueue = []

const processQueue = (error, token = null) => {
  failedQueue.forEach(prom => {
    if (error) {
      prom.reject(error)
    } else {
      prom.resolve(token)
    }
  })

  failedQueue = []
}

// Response interceptor to handle token refresh on 401
axios.interceptors.response.use(
  (response) => {
    return response
  },
  async (error) => {
    const originalRequest = error.config
    const notificationStore = useNotificationStore()

    // If error is 401 and we haven't tried to refresh yet
    if (error.response?.status === 401 && !originalRequest._retry) {
      if (isRefreshing) {
        // If already refreshing, queue this request
        return new Promise((resolve, reject) => {
          failedQueue.push({ resolve, reject })
        })
          .then(token => {
            originalRequest.headers['Authorization'] = 'Bearer ' + token
            return axios(originalRequest)
          })
          .catch(err => {
            return Promise.reject(err)
          })
      }

      originalRequest._retry = true
      isRefreshing = true

      const authStore = useAuthStore()

      try {
        const newToken = await authStore.refreshToken()
        processQueue(null, newToken)

        // Retry the original request with new token
        originalRequest.headers['Authorization'] = 'Bearer ' + newToken
        return axios(originalRequest)
      } catch (refreshError) {
        processQueue(refreshError, null)

        // If refresh fails, show error and redirect to login
        notificationStore.error('Your session has expired. Please log in again.')
        router.push('/login')
        return Promise.reject(refreshError)
      } finally {
        isRefreshing = false
      }
    }

    // Handle other error responses
    if (error.response) {
      const status = error.response.status
      const message = error.response.data?.message || error.message

      switch (status) {
        case 403:
          notificationStore.error('You do not have permission to perform this action.')
          break
        case 404:
          notificationStore.error('The requested resource was not found.')
          break
        case 422:
          // Validation errors - don't show notification, let component handle it
          break
        case 500:
          notificationStore.error('An internal server error occurred. Please try again later.')
          break
        default:
          if (status >= 400 && status < 500) {
            notificationStore.error(message || 'An error occurred. Please try again.')
          } else if (status >= 500) {
            notificationStore.error('A server error occurred. Please contact support.')
          }
      }
    } else if (error.request) {
      // Network error
      notificationStore.error('Network error. Please check your connection and try again.')
    }

    return Promise.reject(error)
  }
)

// Request interceptor to add token to all requests
axios.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore()
    if (authStore.token) {
      config.headers['Authorization'] = 'Bearer ' + authStore.token
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

export default axios
