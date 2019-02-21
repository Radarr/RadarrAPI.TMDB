<template>
    <span><img :src="getPic(rottenPic)" class="rating"/> {{rottenCritics}}%</span>
</template>

<script>
    export default {
        name: "RottenCritics",
        props: ["rottenCritics"],
        computed: {
            rottenPic(){
                if(this.$props.rottenCritics <= 50)
                    return 'bad-rotten-tomato.png';
                else if(this.$props.rottenCritics <= 85)
                    return 'decend-rotten-tomato.png';
                else
                    return 'excellent-rotten-tomato.png';
            }
        },
        methods: {
            getPic(pic) {
                return require('../../assets/' + pic);
            }
        }
    }
</script>

<style scoped>
</style>