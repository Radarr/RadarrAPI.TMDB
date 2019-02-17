<template>
    <el-container>
        <el-header style="padding: 2px 5px;">
            <img src="./assets/LogoDark.svg" alt="Radarr"/>
        </el-header>
        <el-container>
            <el-aside width="180px">
                <navigation></navigation>
            </el-aside>
            <el-main>
                <router-view></router-view>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
    import Lists from './views/Lists';
    import Navigation from './components/Navigation';

    export default {
        name: "app",
        components: {
            Lists,
            Navigation
        }
    }
</script>

<style>
    .el-header {
        background-color: #24292E;
        text-color: white;
        height: 8vh;
    }

    .el-container {
        height: 100%;
    }

    img {
        height: auto;
        width: 165px;
    }

    html, body {
        height:100%;
    }
</style>