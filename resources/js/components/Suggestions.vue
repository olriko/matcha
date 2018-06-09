<template>
    <b-row class="mt-3">
        <b-col sm="12">
            <h3>Our personalized suggestion</h3>
        </b-col>
        <user-list :list="list"></user-list>
    </b-row>
</template>

<script>
    import { mapGetters } from 'vuex'
    import userList  from './UserList'

    export default {
        name: "suggestions",
        props: {
            userId: Number
        },
        components: {
            'user-list': userList
        },
        data() {
            return {
                list: []
            }
        },
        created() {
            this.getSuggestionList();
        },
        computed: {
            ...mapGetters(['jwt'])
        },
        watch: {

        },
        methods: {
            getSuggestionList() {
                axios.post('api/search', {suggestion: true}).then((rep) => {
                    if (rep.status === 200) {
                         this.list = rep.data.results;
                    }
                })
            }
        }
    }

</script>

<style type="scss" scoped>

</style>
