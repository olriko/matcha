<template>
    <div id="app">
        <heading></heading>
        <errors></errors>
        <b-container>
            <router-view></router-view>
        </b-container>
    </div>
</template>

<script>
    import header from './components/Header'
    import errors from './components/Errors'

    export default {
        name: "app",
        components: {
            'heading': header,
            'errors': errors
        }
    }
</script>

<style scoped>

</style>