import { defineStore } from 'pinia'

export const useNotificationStore = defineStore('notification', {
  state: () => ({
    notifications: []
  }),

  actions: {
    show(message, type = 'info', duration = 5000) {
      const id = Date.now()
      const notification = {
        id,
        message,
        type, // 'success', 'error', 'warning', 'info'
        duration
      }

      this.notifications.push(notification)

      // Auto remove after duration
      if (duration > 0) {
        setTimeout(() => {
          this.remove(id)
        }, duration)
      }

      return id
    },

    success(message, duration = 5000) {
      return this.show(message, 'success', duration)
    },

    error(message, duration = 7000) {
      return this.show(message, 'error', duration)
    },

    warning(message, duration = 5000) {
      return this.show(message, 'warning', duration)
    },

    info(message, duration = 5000) {
      return this.show(message, 'info', duration)
    },

    remove(id) {
      const index = this.notifications.findIndex(n => n.id === id)
      if (index > -1) {
        this.notifications.splice(index, 1)
      }
    },

    clear() {
      this.notifications = []
    }
  }
})
