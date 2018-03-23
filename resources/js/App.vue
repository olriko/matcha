<template>
    <div id="app">
        <heading></heading>
        <div class="container">
            <router-view></router-view>
        </div>
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