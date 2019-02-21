<template>
    <el-container>
        <el-main>
            <h1>{{ listTitle }}</h1>
            <p>{{ listDescription }}</p>
            <list-view v-show="listViewActive" v-bind:movies="movies"></list-view>
            <cards-view v-show="!listViewActive" v-bind:movies="movies"></cards-view>
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

    export default {
        name: "lists",
        components: {
            CardsView,
            ListView
        },
        data() {
            return {
                listViewActive: false,
                movies: [
                    {
                        id: 1,
                        name: "TestMovieName1",
                        rating: {
                            rotten: 70,
                            imdb: 5.6
                        }
                    },
                    {
                        id: 2,
                        name: "TestMovieName2",
                        rating: {
                            rotten: 30,
                            imdb: 1.6
                        }
                    },
                    {
                        id: 3,
                        name: "TestMovieName3",
                        rating: {
                            rotten: 90,
                            imdb: 8.6
                        }
                    },
                    {
                        id: 4,
                        name: "TestMovieName4",
                        rating: {
                            rotten: 100,
                            imdb: 10
                        }
                    },
                    {
                        id: 5,
                        name: "TestMovieName5",
                        rating: {
                            rotten: 90,
                            imdb: 8.6
                        }
                    },
                    {
                        id: 6,
                        name: "TestMovieName6",
                        rating: {
                            rotten: 90,
                            imdb: 8.6
                        }
                    },
                    {
                        id: 7,
                        name: "TestMovieName7",
                        rating: {
                            rotten: 90,
                            imdb: 8.6
                        }
                    },
                    {
                        id: 8,
                        name: "TestMovieName8",
                        rating: {
                            rotten: 90,
                            imdb: 8.6
                        }
                    },
                    {
                        id: 9,
                        name: "TestMovieName9",
                        rating: {
                            rotten: 90,
                            imdb: 8.6
                        }
                    },
                ]
            }
        },
        methods: {
            switchTo(id) {
                if(id == "2")
                    this.$data.listViewActive = false;
                else if(id == "1")
                    this.$data.listViewActive = true;
            }
        },
        created() {

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
