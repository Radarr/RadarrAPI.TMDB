import Vue from 'vue'
import Router from 'vue-router'
import Home from '../views/Home';
import Lists from '../views/Lists';
import About from '../views/About';
import MoviePage from '../views/MoviePage';

Vue.use(Router)

export default new Router({
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/lists/:list',
            name: 'lists',
            component: Lists,
        },
        {
            path: '/about',
            name: 'about',
            component: About
        },
        {
            path: '/moviePage',
            name: 'moviePage',
            component: MoviePage,
            props: true
        }
    ]
})