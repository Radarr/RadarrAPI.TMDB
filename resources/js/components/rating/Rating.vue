<template>
    <span v-bind:class="{floatRight: floatRight}">
        <rotten-critics-rating v-if="rottenCriticExists" v-bind:rotten-critics="rottenCritics"></rotten-critics-rating>
        <rotten-rating v-if="rottenExists" v-bind:rotten-rating="rotten"></rotten-rating>
        <imdb-raiting v-if="imdbExists" v-bind:imdb-rating="imdb"></imdb-raiting>
        <!-- <tmdb-raiting v-if="tmdbExists" v-bind:tmdb-rating="tmdb"></tmdb-raiting> -->
        <span v-if="!imdbExists && !rottenExists && !rottenCriticExists">No Rating!</span>
    </span>
</template>

<script>
    import RottenCriticsRating from './RottenCriticsRating';
    import RottenRating from './RottenRating';
    import ImdbRaiting from './ImdbRaiting';
    import TmdbRaiting from './TmdbRaiting';

    export default {
        name: "Rating",
        props: {
            ratings: {
                type: Array
            },
            floatRight: {
                default: false,
                type: Boolean
            }
        },
        components: {
            RottenCriticsRating,
            ImdbRaiting,
            RottenRating,
            TmdbRaiting
        },
        computed: {
            imdbExists() {
                for (let rating of this.ratings) {
                    if (rating.origin == "imdb" && rating.type == "user") {
                        this.imdb = rating;
                        return true;
                    }
                }
                return false;
            },
            tmdbExists() {
                for (let rating of this.ratings) {
                    if (rating.origin == "tmdb" && rating.type == "user") {
                        this.tmdb = rating;
                        return true;
                    }
                }
                return false;
            },
            rottenCriticExists() {
                for (let rating of this.ratings)
                    if(rating.origin == "rt" && rating.type == "critics") {
                        this.rottenCritics = rating;
                        return true;
                    }
                return false;
            },
            rottenExists() {
                for (let rating of this.ratings)
                    if(rating.origin == "rt" && rating.type == "user") {
                        this.rotten = rating;
                        return true;
                    }
                return false;
            }
        },
        data() {
            return {
                imdb: {},
                rottenCritics: {},
                rotten: {},
                tmdb: {}
            }
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