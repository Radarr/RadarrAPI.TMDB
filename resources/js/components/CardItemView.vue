<template>
    <el-card :body-style="{ padding: '0px' }">
        <img src="../assets/cover-dark-radarr.png" class="image">
        <div style="padding: 14px;">
            <span>{{movie.name}}</span>
            <div class="bottom clearfix">
                <span>{{ movie.id }}</span>
                <rating></rating>
            </div>
        </div>
    </el-card>
</template>

<script>
    import Rating from './Rating';

    export default {
        name: "CardItemView",
        props: ["movie"],
        components: {
            Rating
        }
    }
</script>

<style scoped>
    img {
        width: 100%;
    }
</style>