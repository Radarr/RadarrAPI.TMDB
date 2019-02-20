<template>
    <div>
        <span><img src="../assets/decend-rotten-tomato.png" v-bind:datasrc="rottenIs"></span>
    </div>
</template>

<script>
    export default {
        name: "Rating",
        props: ["rating"],
        computed: {
            rottenIs() {
                if(this.$props.rating <= 50)
                    return "/images/bad-rotten-tomato.png";
                else if(this.$props.rating <= 85)
                    return "/images/bad-rotten-tomato.png";
                else
                    return "/images/bad-rotten-tomato.png"
            }
        }
    }
</script>

<style scoped>

</style>