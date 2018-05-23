<template>
    <div>
        <b-row>
            <b-col sm="6" offset-sm="3">
                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title">Login</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Welcome !</h6>
                        <form @submit.prevent="login(user)">
                            <b-form-group label="Email">
                                <b-form-input id="first_name" v-model="user.email"
                                              type="text"
                                              placeholder="Email"></b-form-input>
                            </b-form-group>
                            <b-form-group label="Password">
                                <b-form-input id="password" v-model="user.password"
                                              type="password"
                                              placeholder="Password"></b-form-input>
                            </b-form-group>
                            <!--<b-button type="button" to="home" variant="link">Back</b-button>-->
                            <b-button type="submit" variant="primary">Submit</b-button>
                            <b-button type="button" to="recover" variant="warning">Recover</b-button>
                        </form>
                    </div>
                </div>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import { mapActions } from 'vuex'


    export default {
        name: "login",
        data() {
            return {
                user: {
                    username: '',
                    password: '',
                }
            }
        },
        methods: {
            ...mapActions([
                'login'
            ])
        }
    }
</script>

<style scoped>

</style>