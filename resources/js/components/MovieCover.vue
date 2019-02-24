<template>
    <figure v-lazyload class="image__wrapper">
        <img src="../assets/cover-dark-radarr.png" class="image__spacer">
        <img
            class="image__item"
            :data-url="calcSrc"
            :data-ready="ready"
            alt="../assets/cover-dark-radarr.png"
        >
    </figure>
</template>

<script>
    export default {
        name: "MovieCover",
        props: ["movie"],
        methods: {
        },
        computed: {
            calcSrc() {
                this.ready = true;
                return "https://image.tmdb.org/t/p/w300" + this.$props.movie.poster_path;
            }
        },
        data() {
            return {
                ready: false
            }
        }
    }
</script>

<style scoped lang="scss">
    .image {
        &__wrapper {
            width: auto;
            height: auto;
            align-items: center;
            border-radius: 4px;

            &.loaded {
                .image {
                    &__item {
                        visibility: visible;
                        opacity: 1;
                        border: 0;
                    }

                    &__spacer {
                        display: none;
                        width: 100%;
                        height: auto;
                    }
                }
            }
        }

        &__item {
            width: 100%;
            border-radius: 4px;
            transition: all 0.4s ease-in-out;
            opacity: 0;
            visibility: hidden;
        }

        img {
            width: 100%;
            height: 100%;
        }
    }
</style>
