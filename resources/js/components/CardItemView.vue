<template>
    <el-card :body-style="{ padding: '0px' }">
        <movie-cover v-bind:movie="movie" class="image"></movie-cover>
        <div style="padding: 5px 15px 15px 15px;">
            <span class="movie-name">{{movie.name}}</span>
            <div class="bottom clearfix">
                <!-- <span>{{ movie.id }}</span> -->
                <rating v-bind:ratings="movie.ratings" v-bind:float-right="true"></rating>
            </div>
        </div>
    </el-card>
</template>

<script>
    import Rating from './rating/Rating';
    import MovieCover from './MovieCover';

    export default {
        name: "CardItemView",
        props: ["movie"],
        components: {
            Rating,
            MovieCover
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