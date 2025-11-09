import { defineStore } from 'pinia'

export const useMainStore = defineStore('main', {
  state: () => ({
    pageLoading: true,
  }),

  getters: {
    isLoading: (state) => state.pageLoading,
  },

  actions: {
    toggleLoader() {
      this.pageLoading = !this.pageLoading
    },
  },
})
