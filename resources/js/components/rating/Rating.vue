<template>
    <span v-bind:class="{floatRight: floatRight}">
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
        props: {
            rating: {
                type: Object
            },
            floatRight: {
                default: false,
                type: Boolean
            }
        },
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

    .floatRight {
        float: right;
    }
</style>