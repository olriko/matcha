<template>
    <div>
        <b-row>
            <b-col sm="6" offset-sm="3">
                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title">Register</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Welcome !</h6>
                        <form @submit.prevent="submit">
                            <b-form-group label="Username">
                                <b-form-input v-model="user.username"
                                              type="text"
                                              placeholder="Username"></b-form-input>
                            </b-form-group>
                            <b-row>
                                <b-col sm="6">
                                    <b-form-group label="Firstname">
                                        <b-form-input v-model="user.first_name"
                                                      type="text"
                                                      placeholder="Firstname"></b-form-input>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="6">
                                    <b-form-group label="Lastname">
                                        <b-form-input v-model="user.last_name"
                                                      type="text"
                                                      placeholder="Lastname"></b-form-input>
                                    </b-form-group>
                                </b-col>
                            </b-row>

                            <b-form-group label="Orientation">
                                <b-form-select v-model="user.sexual_orientation" :options="sexual_orientation_options"/>
                            </b-form-group>
                            <b-form-group label="Gender">
                                <b-form-select v-model="user.gender" :options="gender_options"/>
                            </b-form-group>

                            <b-form-group label="Email">
                                <b-form-input v-model="user.email"
                                              type="text"
                                              placeholder="Email"></b-form-input>
                            </b-form-group>

                            <b-form-group label="Password">
                                <b-form-input id="password" v-model="user.password"
                                              type="password"
                                              placeholder="Password"></b-form-input>
                                <b-form-input class="mt-2" id="password_confirmation"
                                              v-model="user.password_confirmation"
                                              type="password"
                                              placeholder="Comfirmation"></b-form-input>
                            </b-form-group>

                            <!--<b-button type="button" to="home" variant="link">Back</b-button>-->
                            <b-button type="submit" variant="primary">Submit</b-button>
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
        name: "register",
        data() {
            return {
                gender_options: [
                    { value: 'male', text: 'Male' },
                    { value: 'female', text: 'Female' },
                    { value: 'other', text: 'Other' },
                ],
                sexual_orientation_options: [
                    { value: 'heterosexual', text: 'Heterosexual' },
                    { value: 'homosexual', text: 'Homosexual' },
                    { value: 'bisexual', text: 'Bisexual' },
                ],
                user: {
                    username: '',
                    first_name: '',
                    last_name: '',
                    email: '',
                    gender: 'other',
                    sexual_orientation: 'bisexual',
                    password: '',
                    password_confirmation: '',
                }
            }
        },
        methods: {
            ...mapActions([
                'login'
            ]),
            submit() {
                axios.post('api/register', this.user).then((res) => {
                    if (res.status === 200) {
                        this.$router.push('login')
                    }
                }).catch((error) => {
                    if (error.response.status === 422) {
                        this.$store.commit('errors', error.response.data.errors)
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>