<template>
    <span>
        <img src="../../assets/imdb_star_22x21.png" class="rating"/>{{imdbRating.voting_average}}/10
    </span>
</template>

<script>
    export default {
        name: "ImdbRating",
        props: ["imdbRating"],
        computed: {
        },
        methods: {
        }
    }
</script>

<style scoped>

</style>