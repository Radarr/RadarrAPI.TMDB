<template>
    <span>T: {{tmdbRaiting.voting_average}}%</span>
</template>

<script>
    export default {
        name: "TmdbRaiting",
        props: ["tmdbRaiting"],
    }
</script>

<style scoped>

</style>