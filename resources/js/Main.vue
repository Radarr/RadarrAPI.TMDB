<template>
    <el-container>
        <el-header>
            <div class="header-logo">
                <router-link to="/">
                    <img style="width:165px" src="./assets/LogoDark.svg" alt="Radarr" />
                </router-link>
                    <span class="header-text">Apollo API</span>
            </div>
        </el-header>
        <el-container>
            <el-aside width="250px">
                <navigation></navigation>
            </el-aside>
            <el-main style="padding: 0">
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
        },
        data() {
            return {
            }
        }
    }
</script>

<style>
    .el-header {
        background-color: #24292E;
        color: white;
        height: 8vh;
    }

    .el-container {
        height: 100%;
    }

    img {
        height: auto;
    }

    html, body {
        height:100%;
    }

    .header-logo {
        display: flex;
        align-items: center;
        margin-top: 5px;
    }

    .header-text {
        font-size: 24pt;
        padding-left: 20px;
    }

    span {
        font-family: 'Century Gothic';
    }
</style>