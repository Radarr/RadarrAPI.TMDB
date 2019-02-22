<template>
    <div>
        <h1>{{movie.name}}</h1>
        <span> Text of movie {{movie.id}}</span>
        <rating v-bind:rating="movie.rating"></rating>
    </div>
</template>

<script>
    import Rating from "./rating/Rating";
    export default {
        name: "MoviePage",
        components: {Rating},
        props: {
            movie: {
                type: Object,
                required: true
            }
        },
        computed: {
        }
    }
</script>

<style scoped>

</style>