<template>
    <div>
        <b-row>
            <b-col sm="6" offset-sm="3">
                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title">Recover</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Welcome !</h6>
                        <form @submit.prevent="recover">
                            <b-form-group label="Email">
                                <b-form-input id="first_name" v-model="email"
                                              type="text"
                                              placeholder="Email"></b-form-input>
                            </b-form-group>
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
        name: "Recover",
        data() {
          return {
              email: ''
          }
        },
        methods: {
            recover() {
                axios.post('recover', {}).then((res) => {

                })
            }
        }
    }
</script>

<style scoped>

</style>