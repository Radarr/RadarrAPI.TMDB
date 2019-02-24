<template>
    <span><img :src="getPic(rottenPic)" class="rating"/> {{rottenRating.voting_average}}%</span>
</template>

<script>
    export default {
        name: "RottenRating",
        props: ["rottenRating"],
        computed: {
            rottenPic(){
                if(this.$props.rottenRating < 70)
                    return 'decend-rotten-tomato2.png';
                else
                    return 'excellent-rotten-tomato2.png';
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