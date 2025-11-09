import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const routes = [
  {
    path: '/',
    redirect: {
      name: 'dashboard',
    },
  },
  {
    path: '/login',
    name: 'login',
    beforeEnter: (to, from, next) => {
      const authStore = useAuthStore()
      if (authStore.isAuthenticated) {
        next({ name: 'dashboard' })
      } else {
        next()
      }
    },
    component: () => import('../views/Login.vue'),
  },
  {
    path: '/dashboard',
    component: () => import('../Layout.vue'),
    children: [
      {
        path: '',
        name: 'dashboard',
        component: () => import('../views/Dashboard.vue'),
      },
      {
        path: 'collaborators',
        name: 'collaborators',
        component: () => import('@/views/Collaborators/index.vue'),
      },
      {
        path: 'collaborators/create',
        name: 'add collaborator',
        component: () => import('@/views/Collaborators/create.vue'),
      },
      {
        path: 'collaborators/:id/edit',
        name: 'edit collaborator',
        component: () => import('@/views/Collaborators/edit.vue'),
      },
      {
        path: 'settings',
        name: 'settings',
        component: () => import('@/views/User/settings.vue'),
      },
      {
        path: 'collaborators/:id/profile',
        name: 'profile',
        component: () => import('@/views/Collaborators/show.vue'),
      },
      {
        path: 'collaborators/archive',
        name: 'archive',
        component: () => import('@/views/Collaborators/archive.vue'),
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const loggedIn = authStore.isAuthenticated
  const user = authStore.user

  if (to.name !== 'login' && !loggedIn) {
    next({ name: 'login' })
  } else if (user) {
    const hasViewPermission = user.permissions?.includes('view collaborators')
    if (hasViewPermission || to.name === 'settings') {
      next()
    } else if (to.name !== 'profile' || to.params.id !== user.id) {
      next({ name: 'profile', params: { id: user.id } })
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router
