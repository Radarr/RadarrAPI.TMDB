<template>
    <el-row>
        <el-col :xs="12" :sm="6" :md="4" v-for="movie in movies" v-bind:key="movie.id">
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

</style>