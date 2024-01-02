const routes = [
  // main routes
  {
    path: "/",
    component: () => import("@/js/layouts/MainLayout.vue"),
    children: [
      {
        path: "/",
        name: "home",
        component: () => import('@/js/pages/index.vue')
        // meta: { requiresAuth: true }
      },
      {
        path: "/catalog",
        name: "catalog",
        component: () => import('@/js/pages/Catalog.vue')
        // meta: { requiresAuth: true }
      },
      {
        path: "/order-summary",
        name: "order-summary",
        component: () => import('@/js/pages/OrderSummary.vue')
        // meta: { requiresAuth: true }
      },
      {
        path: "/about",
        name: "about",
        component: () => import('@/js/pages/About.vue')
        // meta: { requiresAuth: true }
      }
    ]
  },
  {
    path: "/test",
    component: () => import('@/js/pages/Test.vue')
  }
]

export default routes
