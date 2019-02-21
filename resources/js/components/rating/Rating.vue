<template>
    <span style="float: right">
        <rotten-critics-rating v-bind:rotten-critics="rating.rotten"></rotten-critics-rating>
        <rotten-rating v-bind:rotten-rating="rating.rotten"></rotten-rating>
        <imdb-rating v-bind:imdb-rating="rating.imdb"></imdb-rating>
    </span>
</template>

<script>
    import RottenCriticsRating from './RottenCriticsRating';
    import RottenRating from './RottenRating';
    import ImdbRating from './ImdbRating';

    export default {
        name: "Rating",
        props: ["rating"],
        components: {
            RottenCriticsRating,
            ImdbRating,
            RottenRating
        }
    }
</script>

<style>
    .rating {
        width: 20px;
        height: auto;
        padding-bottom: 3px;
    }
</style>