<template>
    <div>
        <b-row>
            <b-col sm="6" offset-sm="3">
                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title">Recover</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Welcome !</h6>
                        <form @submit.prevent="changePassword">
                            <b-form-group label="password">
                                <b-form-input v-model="password"
                                              type="password"
                                              placeholder="New password"></b-form-input>
                            </b-form-group>
                            <b-form-group label="password">
                                <b-form-input v-model="password2"
                                              type="password"
                                              placeholder="Confirm password"></b-form-input>
                            </b-form-group>
                            <div v-if="error" class="error">{{ error }}</div>
                            <b-button type="button" to="login" variant="link">Back</b-button>
                            <b-button type="submit" variant="primary">Submit</b-button>
                        </form>
                    </div>
                </div>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    export default {
        name: "ChangePassword",
        data() {
            return {
                password: '',
                password2: '',
                error: ''
            }
        },
        methods: {
            changePassword() {
                if (this.password !== this.password2) {
                    this.error = 'Passwords do not match !';
                    return;
                }

                this.error = '';
                axios.post('api/new-password', {
                    password: this.password,
                    id: this.$route.params['id'],
                    token: this.$route.params['token'],
                }).then((res) => {
                    if (res.status === 200) {
                        return this.$router.push({ name: 'login' })
                    }
                    this.error = 'Something went wrong, try to get the reset link again !';
                })
            }
        }
    }
</script>

<style scoped>

    .error {
        text-align: center;
        color: red;
        font-weight: bold;
        padding: 1rem;
        border: 1px solid red;
        margin: 1rem 0;
    }

</style>