<template>
    <div>
        <h1>{{movie.name}}</h1>
    </div>
</template>

<script>
    export default {
        name: "CardItemView",
        props: ["movie"]
    }
</script>

<style scoped>

</style>