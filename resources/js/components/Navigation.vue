<template>
    <el-row>
        <el-col>
            <el-menu
                    v-bind:router="true"
                    :default-openeds="['/lists']"
                    :default-active=this.$route.fullPath
                    background-color="#24292E"
                    text-color="#fff"
                    active-text-color="#FFC230">
                <el-menu-item index="/" route="/">
                    <i class="el-icon-menu"></i>
                    <span>Home</span>
                </el-menu-item>
                <el-submenu index="/lists">
                    <template slot="title">
                        <i class="el-icon-document"></i>
                        <span>Lists</span>
                    </template>
                    <el-menu-item index="/lists/imdb_top250" route="/lists/imdb_top250">
                        IMDB Top 250
                    </el-menu-item>
                </el-submenu>
                <el-menu-item index="/about" route="/about">
                    <i class="el-icon-setting"></i>
                    <span>About</span>
                </el-menu-item>
            </el-menu>
        </el-col>
    </el-row>
</template>

<script>
    export default {
        name: "Navigation",
        created() {

        }
    }
</script>

<style scoped>
    .el-menu, .el-row, .el-col {
        height: 100%;
    }
</style>