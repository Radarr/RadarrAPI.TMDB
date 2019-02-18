<template>
    <el-container>
        <el-main>
            <list-view v-show="listViewActive" v-bind:movies="movies"></list-view>
            <cards-view v-show="!listViewActive" v-bind:movies="movies"></cards-view>
        </el-main>
        <el-footer>
            <el-button @click="switchTo('1')">ListView</el-button>
            <el-button @click="switchTo('2')">CardView</el-button>
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
                        rotten: 70,
                        imdb: 5.6
                    }
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

        }
    }
</script>

<style scoped>

</style>
