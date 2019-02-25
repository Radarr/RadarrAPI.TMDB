<template>
    <el-card :body-style="{ padding: '0px' }">
        <movie-cover v-bind:movie="movie" class="image"></movie-cover>
        <div style="padding: 5px 10px 15px 10px;">
            <el-row>
                <el-col :md="24">
                    <span class="movie-name">{{strippedTitle}}</span>
                </el-col>
                <el-col :md="24">
                    <div class="bottom clearfix">
                        <!-- <span>{{ movie.id }}</span> -->
                        <rating v-bind:ratings="movie.ratings" v-bind:float-right="true"></rating>
                    </div>
                </el-col>
            </el-row>
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
        },
        computed: {
            strippedTitle() {
                if(this.windowWidth < 768) {
                    return this.movie.original_title;
                }
                if(this.windowWidth <= 1200 && this.windowWidth > 991 && this.movie.original_title.length > 20) {
                    return this.movie.original_title.slice(0, 20) + "...";
                } else if(this.windowWidth < 1400 && this.windowWidth > 1199 && this.movie.original_title.length > 12) {
                    return this.movie.original_title.slice(0, 12) + "...";
                }
                return this.movie.original_title;
            }
        },
        data() {
            return {
                windowWidth: window.innerWidth
            }
        }
    }
</script>

<style scoped>
    .image {
        width: 100%;
    }

    .movie-name {
        font-size: larger;
    }

    .el-card__body:before {
        filter: blur(10px);
    }
</style>