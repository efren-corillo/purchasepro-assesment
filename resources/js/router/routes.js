const routes = [
    // main routes
    {
        path: "/",
        name: "home",
        component: () => import("../pages/index.vue")
        // meta: { requiresAuth: true }
    },
];

export default routes;
