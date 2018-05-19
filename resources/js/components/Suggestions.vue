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
                list: null
            }
        },
        created() {
            this.getSuggestionList();
            this.list = this.list = [
                {
                    id: 3,
                    first_name: 'Mathieu',
                    last_name: 'Ceccato',
                    gender: 'male',
                    description: 'qwertyuiop asdfghjkl zxcvbnm',
                    score: 1234,
                    birthday: '1998-01-29'
                },
                {
                    id: 4,
                    first_name: 'Olivier',
                    last_name: 'Hamon',
                    gender: 'male',
                    description: 'qwertyuiop asdfghjkl zxcvbnm',
                    score: 1234,
                    birthday: '1995-01-29'
                },
            ];
        },
        computed: {

        },
        watch: {

        },
        methods: {
            getSuggestionList() {
                axios.get(`api/suggestions/${this.userId}`).then((rep) => {
                    if (rep.status === 200) {
                         this.list = rep.list;
                    }
                })
            }
        }
    }

</script>

<style type="scss" scoped>

</style>
