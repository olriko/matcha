<template>
    <b-row>
        <b-col v-for="user in list" :key="user.id" sm="12" md="6" lg="4">
            <user-card :user="user"></user-card>
        </b-col>
    </b-row>
</template>

<script>
    import { mapGetters } from 'vuex'
    import userCard  from './UserCard'

    export default {
        name: "user-list",
        props: {
            list: Array
        },
        components: {
            'user-card': userCard
        },
    }

</script>

<style type="scss" scoped>

</style>
