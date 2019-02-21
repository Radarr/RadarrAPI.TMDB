<template>
    <span>
        <rotten-critics v-bind:rotten-critics="rating.rotten"></rotten-critics>
    </span>
</template>

<script>
    import RottenCritics from './RottenCritics';

    export default {
        name: "Rating",
        props: ["rating"],
        components: {
            RottenCritics
        }
    }
</script>

<style>
    .rating {
        width: 22px;
        height: auto;
    }
</style>