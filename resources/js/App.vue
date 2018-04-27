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

    import cookies from 'js-cookie';


    export default {
        name: "app",
        components: {
            'heading': header,
            'errors': errors
        },
        created()
        {
            let self = this
            let token = cookies.get('token');
            if (token) {
                this.$store.commit('jwt', token);
            }

            axios.interceptors.response.use(function (response) {
                return response;
            }, function (error) {
                if (error.response.status === 401) {
                    cookies.remove('token');
                    delete window.axios.defaults.headers['TOKEN'];
                    self.$store.commit('jwt');
                    self.$router.push('login');
                }
                return Promise.reject(error);
            });
        }
    }
</script>

<style scoped>

</style>