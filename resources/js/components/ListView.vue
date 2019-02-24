<template>
    <el-table
            :data="movies"
            style="width: 100%"
            @row-click="loadMovie">
        <el-table-column
                prop="id"
                label="ID"
                width="80">
        </el-table-column>
        <el-table-column
                prop="original_title"
                label="Movie Title"
                width="180">
        </el-table-column>
        <el-table-column label="Rating">
            <div slot-scope="{row}" class="td-number">
                <rating v-bind:ratings="row.ratings"></rating>
            </div>
        </el-table-column>
    </el-table>
</template>

<script>
    import Rating from "./rating/Rating";
    export default {
        name: "ListView",
        components: {
            Rating
        },
        props: ["movies"],
        methods: {
            loadMovie(movie) {
                this.$router.push({ name: 'moviePage', params: { movie: movie } });
            }
        }
    }
</script>

<style scoped>
</style>