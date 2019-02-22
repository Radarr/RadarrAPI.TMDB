<template>
    <el-card :body-style="{ padding: '0px' }">
        <img src="../assets/cover-dark-radarr.png" class="image">
        <div style="padding: 5px 15px 15px 15px;">
            <span class="movie-name">{{movie.name}}</span>
            <div class="bottom clearfix">
                <!-- <span>{{ movie.id }}</span> -->
                <rating v-bind:rating="movie.rating" v-bind:float-right="true"></rating>
            </div>
        </div>
    </el-card>
</template>

<script>
    import Rating from './rating/Rating';

    export default {
        name: "CardItemView",
        props: ["movie"],
        components: {
            Rating
        }
    }
</script>

<style scoped>
    img {
        width: 100%;
    }

    .movie-name {
        font-size: larger;
    }
</style>