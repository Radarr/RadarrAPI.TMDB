<template>
    <div class="outer">
        <div class="bg" v-bind:style="{ backgroundImage: 'url(' + calcUrl + ')' }"></div>
        <div class="upper">
            <el-row>
                <el-col :sm="6" :md="5" :lg="4">
                    <movie-cover v-bind:movie="movie"></movie-cover>
                </el-col>
                <el-col :sm="18" :md="19" :lg="20">
                    <el-container>
                        <el-header style="height: auto;">
                                    <el-row>
                                        <el-col :md="20">
                                            <h2>{{movie.original_title}}</h2>
                                        </el-col>
                                        <el-col :md="4">
                                            <span style="float: right">ReleaseDate: {{movie.release_date}}</span>
                                        </el-col>
                                        <el-col :md="24">
                                            <span>MovieID:{{movie.id}}</span>
                                            <rating v-bind:float-right="true" v-bind:ratings="movie.ratings"></rating>
                                        </el-col>
                                    </el-row>
                        </el-header>
                        <el-main>
                            <p>{{movie.overview}}</p>
                        </el-main>
                    </el-container>
                </el-col>
            </el-row>
        </div>
        <div class="lower">
            <span><h1>Test Test Test Test</h1></span>
        </div>
    </div>
</template>

<script>
    import Rating from "../components/rating/Rating";
    import MovieCover from '../components/MovieCover';

    export default {
        name: "MoviePage",
        components: {
            Rating,
            MovieCover
        },
        props: {
            movie: {
                type: Object,
                required: true
            }
        },
        computed: {
            calcUrl() {
                return "https://image.tmdb.org/t/p/w1280/" + this.$props.movie.backdrop_path;
            }
        }
    }
</script>

<style scoped>
    .upper {
        padding: 15px 15px 0 15px;
    }

    .lower {
        padding: 0 15px 0 15px;
    }

    .outer {
        height: 100%;
        z-index: 1;
    }

    .bg {
        position: absolute;
        z-index: -1;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        opacity: .25;
        width: 100%;
        height: 100%;
        background-size:     cover;                      /* <------ */
        background-repeat:   no-repeat;
        background-position: center center;
    }

    .el-header {
        background-color: transparent;
        color: #24292E;
    }

    .el-main {
        padding-top: 15px;
    }

    movie-cover {
        height: 20vh;
    }
</style>