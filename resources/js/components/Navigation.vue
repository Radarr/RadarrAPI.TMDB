<template>
    <el-row>
        <el-col>
            <el-menu
                    v-bind:router="true"
                    default-active="2"
                    background-color="#24292E"
                    text-color="#fff"
                    active-text-color="#FFC230">
                <el-menu-item index="1" route="/">
                    <i class="el-icon-menu"></i>
                    <span>Home</span>
                </el-menu-item>
                <el-menu-item index="2" route="/lists">
                    <i class="el-icon-document"></i>
                    <span>Lists</span>
                </el-menu-item>
                <el-menu-item index="3" route="/about">
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
            this.$router.push("/lists")
        }
    }
</script>

<style scoped>
    .el-menu, .el-row, .el-col {
        height: 100%;
    }
</style>