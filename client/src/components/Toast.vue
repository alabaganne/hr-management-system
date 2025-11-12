<template>
  <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div
      v-for="notification in notifications"
      :key="notification.id"
      class="toast show"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
    >
      <div class="toast-header" :class="getHeaderClass(notification.type)">
        <strong class="me-auto">{{ getTitle(notification.type) }}</strong>
        <button
          type="button"
          class="btn-close"
          @click="remove(notification.id)"
          aria-label="Close"
        ></button>
      </div>
      <div class="toast-body">
        {{ notification.message }}
      </div>
    </div>
  </div>
</template>

<script>
import { useNotificationStore } from '@/store/notification'
import { computed } from 'vue'

export default {
  name: 'Toast',
  setup() {
    const notificationStore = useNotificationStore()

    const notifications = computed(() => notificationStore.notifications)

    const getHeaderClass = (type) => {
      const classes = {
        success: 'bg-success text-white',
        error: 'bg-danger text-white',
        warning: 'bg-warning',
        info: 'bg-info text-white'
      }
      return classes[type] || classes.info
    }

    const getTitle = (type) => {
      const titles = {
        success: 'Success',
        error: 'Error',
        warning: 'Warning',
        info: 'Info'
      }
      return titles[type] || 'Notification'
    }

    const remove = (id) => {
      notificationStore.remove(id)
    }

    return {
      notifications,
      getHeaderClass,
      getTitle,
      remove
    }
  }
}
</script>

<style scoped>
.toast {
  min-width: 300px;
  margin-bottom: 0.5rem;
}
</style>
