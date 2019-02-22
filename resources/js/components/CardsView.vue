<template>
    <el-row :gutter="10" class="row-bg" justify="left">
        <el-col :xs="22" :sm="8" :md="6" :lg="4" v-for="movie in movies" v-bind:key="movie.id">
            <div @click="loadMovie(movie)">
                <card-item-view v-bind:movie="movie"></card-item-view>
            </div>
        </el-col>
    </el-row>
</template>

<script>
    import CardItemView from './CardItemView';

    export default {
        name: "CardsView",
        props: ["movies"],
        components: {
            CardItemView
        },
        methods: {
            loadMovie(movie) {
                this.$router.push({ name: 'moviePage', params: { movie: movie } });
            }
        }
    }
</script>

<style scoped>
    .el-col {
        padding-bottom: 15px;
    }
</style>