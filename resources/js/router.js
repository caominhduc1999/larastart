import Vue from 'vue';
import Router from 'vue-router';
import Dashboard from './views/Dashboard.vue';
import Categories from './views/Categories.vue';
import Register from "./views/authentication/Register.vue";
import Home from "./views/Home";
import Login from "./views/authentication/Login";
import ResetPassword from "./views/authentication/ResetPassword";
import * as auth from './services/auth_service.js';
import Products from "./views/Products";
import Error404 from "./views/Error404";

Vue.use(Router);

const routes = [
    {
        path: '/home',
        // name: 'home',
        component: Home,
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Dashboard
            },
            {
                path: 'categories',
                name: 'categories',
                component: Categories,
                beforeEnter(to, from, next){
                    if(auth.getUserRole() === 'administrator'){
                        next();
                    }else{
                        next('/404');
                    }
                }
            },
            {
                path: 'products',
                name: 'products',
                component: Products,
                beforeEnter(to, from, next){
                    if(auth.getUserRole() === 'user'){
                        next();
                    }else{
                        next('/404');
                    }
                }
            },
        ],
        beforeEnter(to, from, next){
            if(!auth.isLoggedIn()){
                next('/login');
            }else{
                next();
            }
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        beforeEnter(to, from, next){
            if(!auth.isLoggedIn()){
                next();
            }else{
                next('/home');
            }
        }
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: ResetPassword
    },
    {
        path: '*',
        name: '404',
        component: Error404
    },
]

const router = new Router({
    mode: 'history',
    routes: routes,
    linkActiveClass: 'active'
})

export default router;
