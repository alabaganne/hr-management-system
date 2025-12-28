import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
  }),

  getters: {
    isAuthenticated: (state) => state.token !== null,
  },

  actions: {
    async attempt(token) {
      if (!token) {
        return // there's no point of sending a token to retrieve the user info if token is null
      }

      try {
        const response = await axios.get('/auth/me', {
          headers: {
            Authorization: 'Bearer ' + token,
          },
        })

        this.setToken(token)
        this.setUser(response.data.data)
        return response
      } catch (error) {
        if (error.response?.status === 401) {
          this.setToken(null)
          localStorage.removeItem('token')
        }
        throw error
      }
    },

    async logout() {
      if (this.token) {
        try {
          await axios.post('/auth/logout')
        } catch (error) {
          console.log(error)
        } finally {
          this.setToken(null)
          this.setUser(null)
        }
      }
    },

    setToken(token) {
      this.token = token
      if (token) {
        localStorage.setItem('token', token)
      } else {
        localStorage.removeItem('token')
      }
    },

    setUser(user) {
      this.user = user
    },

    updateUser(user) {
      this.setUser(user)
    },

    async refreshToken() {
      try {
        const response = await axios.post('/auth/refresh')
        const newToken = response.data.access_token

        this.setToken(newToken)
        return newToken
      } catch (error) {
        console.error('Token refresh failed:', error)
        // If refresh fails, logout the user
        this.setToken(null)
        this.setUser(null)
        throw error
      }
    },
  },
})
