<template>
    <b-navbar toggleable="md" type="dark" variant="dark">

        <div class="container">
            <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>

            <b-navbar-brand to="home">Matcha</b-navbar-brand>

            <b-collapse is-nav id="nav_collapse">
                <b-navbar-nav>
                    <b-nav-item target="_blank" href="/matcha.fr.pdf">PDF</b-nav-item>
                    <b-nav-item href="#" disabled>Disabled</b-nav-item>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav v-if="user" class="ml-auto">
                    <b-nav-item-dropdown right>
                        <!-- Using button-content slot -->
                        <template slot="button-content">
                            <em>User</em>
                        </template>
                        <b-dropdown-item href="#">Profile</b-dropdown-item>
                        <b-dropdown-item href="#">Signout</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
                <b-navbar-nav v-else class="ml-auto">
                    <b-nav-item to="login">Login</b-nav-item>
                    <b-nav-item to="register">Register</b-nav-item>
                </b-navbar-nav>

            </b-collapse>
        </div>
    </b-navbar>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
        name: "header-nav",
        mounted() {
        },
        data() {
            return {}
        },
        computed: {
            ...mapState([
                'user',
            ])
        }
    }
</script>

<style scoped>

</style>