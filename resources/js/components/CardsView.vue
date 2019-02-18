<template>
    <div>
    <div v-for="movie in movies" v-bind:key="movie.id">
        <card-item-view v-bind:movie="movie"></card-item-view>
    </div></div>
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