<template>
    <div>
        <b-row>
            <b-col sm="6">
                <images v-if="user" :pictures="user.images"></images>
                <tags v-if="user" :tags="user.tags"></tags>
                <location v-if="user"></location>
            </b-col>
            <b-col sm="6">
                <div v-if="user"  class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title">Settings</h5>
                        <form @submit.prevent="updateUser">
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

                            <b-form-group label="Description">
                                <b-form-textarea  :rows="3"
                                                  :max-rows="6"
                                                  placeholder="Enter something"
                                                  v-model="user.description"/>
                            </b-form-group>
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
                            <b-form-group label="Birthday">
                                <b-form-input v-model="user.birthday"
                                              type="date"
                                              placeholder="Birthday"></b-form-input>
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

                            <b-button type="button" to="home" variant="link">Back</b-button>
                            <b-button type="submit" variant="primary">Save</b-button>
                        </form>
                    </div>
                </div>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import SettingsTags from './SettingsTags'
    import SettingsImages from './SettingsImages'
    import SettingsLocation from './SettingsLocation'


    export default {
        name: "settings",
        components: {
            tags: SettingsTags,
            images: SettingsImages,
            location: SettingsLocation
        },
        data() {
            return {
                gender_options: [
                    { value: null, text: 'Please select an option' },
                    { value: 'male', text: 'Male' },
                    { value: 'female', text: 'Female' },
                    { value: 'other', text: 'Other' },
                ],
                sexual_orientation_options: [
                    { value: null, text: 'Please select an option' },
                    { value: 'heterosexual', text: 'Heterosexual' },
                    { value: 'homosexual', text: 'Homosexual' },
                    { value: 'bisexual', text: 'Bisexual' },
                ],
                user: null
            }
        },
        mounted() {
            if (this.currentUser) {
                this.getUser(this.currentUser)
            } else {
                this.$router.push('login')
            }
        },
        computed: {
            ...mapGetters(['currentUser'])
        },
        methods: {
            getUser(id) {
                if (id) {
                    axios.get(`api/user/${id}/edit`).then((rep) => {
                        if (rep.status === 200) {
                            this.user = rep.data.user
                        }
                    })
                }
            },
            updateUser() {
                axios.put(`api/user`, this.user).then((rep) => {
                    if (rep.status === 200) {
                        console.log('ok')
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>