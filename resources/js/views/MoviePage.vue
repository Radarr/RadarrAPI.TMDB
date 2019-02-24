<template>
    <div>
        <h1>{{movie.original_title}}</h1>
        <span> Text of movie {{movie.id}}</span>
        <rating v-bind:ratings="movie.ratings"></rating>
    </div>
</template>

<script>
    import Rating from "../components/rating/Rating";
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