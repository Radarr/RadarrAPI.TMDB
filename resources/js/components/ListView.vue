<template>
    <el-table
            :data="movies"
            style="width: 100%">
        <el-table-column
                prop="id"
                label="ID"
                width="80">
        </el-table-column>
        <el-table-column
                prop="name"
                label="MovieName"
                width="180">
        </el-table-column>
        <el-table-column label="Rating">
        </el-table-column>
    </el-table>
</template>

<script>
    export default {
        name: "ListView",
        props: ["movies"]
    }
</script>

<style scoped>
</style>