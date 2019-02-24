<template>
    <el-container>
        <el-main>
            <h1>{{ listTitle }}</h1>
            <p>{{ listDescription }}</p>
            <div v-show="movies.length > 0">
                <list-view v-show="listViewActive" v-bind:movies="movies"></list-view>
                <cards-view v-show="!listViewActive" v-bind:movies="movies"></cards-view>
            </div>
            <h1 v-show="movies.length < 1">TODO: Currently fetching data from server!</h1>
        </el-main>
        <el-footer>
            <el-button @click="switchTo('1')" v-bind:class="{active: listViewActive}">ListView</el-button>
            <el-button @click="switchTo('2')" v-bind:class="{active: !listViewActive}">CardView</el-button>
        </el-footer>
    </el-container>
</template>

<script>
    import CardsView from '../components/CardsView';
    import ListView from '../components/ListView';
    import axios from 'axios';

    export default {
        name: "lists",
        components: {
            CardsView,
            ListView
        },
        data() {
            return {
                listViewActive: false,
                movies: [],
                response: {}
            }
        },
        created() {
            axios
                .get('/api/movies')
                .then(response => {
                    this.movies = response.data.data;
                    this.response = response;
                })
        },
        methods: {
            switchTo(id) {
                if(id == "2")
                    this.$data.listViewActive = false;
                else if(id == "1")
                    this.$data.listViewActive = true;
            }
        },
        computed : {
            listTitle() {
                switch (this.$route.params.list) {
                    case "imdb_top250": return "IMDB Top 250";
                }
            },
            listDescription() {
                switch (this.$route.params.list) {
                    case "imdb_top250": return "The top 250 movies according to IMDB rating."
                }
            }
        }
    }
</script>

<style scoped>
    .el-button {
        float: right;
        margin: 5px;
    }

    .active {
        background-color: #525960;
        color: white;
    }
</style>
