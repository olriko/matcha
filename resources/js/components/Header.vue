<template>
    <b-navbar toggleable="md" type="dark" variant="dark">

        <div class="container">
            <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>

            <b-navbar-brand :to="{name: 'home'}">Matcha</b-navbar-brand>

            <b-collapse is-nav id="nav_collapse">
                <b-navbar-nav>
                    <b-nav-item target="_blank" href="/matcha.fr.pdf">PDF</b-nav-item>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav v-if="jwt" class="ml-auto">
                    <b-nav-item-dropdown right>
                        <!-- Using button-content slot -->
                        <template slot="button-content">
                            <em>User</em>
                        </template>
                        <b-dropdown-item :to="{name: 'profile'}">Profile</b-dropdown-item>
                        <b-dropdown-item :to="{name: 'settings'}">Settings</b-dropdown-item>
                        <b-dropdown-item @click="logout">Logout</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
                <b-navbar-nav v-else class="ml-auto">
                    <b-nav-item :to="{ name: 'login' }">Login</b-nav-item>
                    <b-nav-item :to="{ name: 'register' }">Register</b-nav-item>
                </b-navbar-nav>

            </b-collapse>
        </div>
    </b-navbar>
</template>

<script>
    import { mapState } from 'vuex'
    import { mapActions } from 'vuex'


    export default {
        name: "header-nav",
        mounted() {
        },
        data() {
            return {}
        },
        computed: {
            ...mapState([
                'jwt',
            ])
        },
        methods: {
            ...mapActions([
                'logout'
            ])
        }
    }
</script>

<style scoped>

</style>