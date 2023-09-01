import {createRouter, createWebHistory} from 'vue-router'

const Home = () => import("../view/Home.vue");
const NotFound = () => import("../view/404.vue");

const routes = [
    { 
        path        : '/', 
        component   : Home,
        meta        : {
            title : "Weather App",
        },
    },
    { 
        path        : '/:pathMatch(.*)*',  
        name        : "NotFound",
        component   : NotFound,
        meta        : {
            title : "Page Not Found"
        }
    },
  ];

const router = createRouter({
    history: createWebHistory(),
    base: '/',
    routes,
});

router.beforeEach(async (to, from, next) => {
    document.title = to.meta.title ?? "Weather App"
    next(); 
})

export default router;