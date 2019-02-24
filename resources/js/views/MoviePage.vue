<template>
    <div>
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
    </div>
</template>

<script>
    import Rating from "../components/rating/Rating";
    export default {
        name: "MoviePage",
        components: {Rating},
        props: {
            movie: {
                type: Object,
                required: true
            }
        },
        computed: {
        }
    }
</script>

<style scoped>
    .el-container {
        padding-top: 20px;
    }

    .el-header {
        background-color: white;
        color: #24292E;
    }

    .el-main {
        padding-top: 10px;
    }
</style>