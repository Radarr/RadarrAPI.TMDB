<template>
    <el-row :gutter="10" class="row-bg" justify="left">
        <el-col :xs="22" :sm="8" :md="6" :lg="4" v-for="movie in movies" v-bind:key="movie.id">
            <card-item-view v-bind:movie="movie"></card-item-view>
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
        }
    }
</script>

<style scoped>
    .el-col {
        padding-bottom: 15px;
    }
</style>