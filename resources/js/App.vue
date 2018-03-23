<template>
    <div id="app">
        <heading></heading>
        <b-container>
            <router-view></router-view>
        </b-container>
    </div>
</template>

<script>
    import header from './components/Header'

    export default {
        name: "app",
        components: {
            'heading': header
        }
    }
</script>

<style scoped>

</style>