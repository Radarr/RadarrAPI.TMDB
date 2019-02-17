import Vue from 'vue'
import Router from 'vue-router'
import Home from '../views/Home';
import Lists from '../views/Lists';
import About from '../views/About';

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/lists',
            name: 'lists',
            component: Lists
        },
        {
            path: '/about',
            name: 'about',
            component: About
        }
    ]
})