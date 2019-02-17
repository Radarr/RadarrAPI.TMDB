<template>
    <el-container>
        <el-main>
            <list-view v-show="listViewActive"></list-view>
            <cards-view v-show="cardViewActive"></cards-view>
            <div v-show="noViewActive">
                <h1>There is no view active.</h1>
            </div>
        </el-main>
        <el-footer>
            <el-button @click="switchTo">Test</el-button>
        </el-footer>
    </el-container>
</template>

<script>
    import CardsView from '../components/CardsView';
    import ListView from '../components/ListView';

    export default {
        name: "lists",
        components: {
            CardsView,
            ListView
        },
        data() {
            return {
                listViewActive: false,
                cardViewActive: true
            }
        },
        computed: {
            noViewActive: app => !app.listViewActive && !app.cardViewActive
        },
        methods: {
            switchTo() {
                listViewActive = !listViewActive;
                cardViewActive = !cardViewActive;
            }
        }
    }
</script>

<style scoped>

</style>
