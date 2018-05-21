<template>
    <div v-if="user">
        <b-row class="mt-3">
            <b-col sm="3">
                <b-card v-if="avatar" overlay
                        :img-src="'/storage/avatars/' + avatar.name"
                        text-variant="primary"
                        img-alt="Avatar">
                </b-card>
                <p v-if="!avatar" class="text-danger text-center">No avatar</p>
                <h4 class="mt-2">Score <b-badge class="float-right" pill variant="success">{{ user.score }}</b-badge></h4>
                <h4>Likes</h4>
                <b-list-group class="mt-3">
                    <b-list-group-item :to="{name: 'user', params: {id: like.id }}" v-for="like in user.likes" :key="like.id">{{ like.name }}  <b-badge class="float-right" size="sm" variant="danger">{{ like.dt | luxon.diffForHumans }}</b-badge></b-list-group-item>
                </b-list-group>
                <h4>Visits</h4>
                <b-list-group class="mt-3">
                    <b-list-group-item :to="{name: 'user', params: {id: visit.user_id }}" v-for="visit in user.visits" :key="visit.id">{{ visit.name }}  <b-badge class="float-right" size="sm" variant="primary">{{ visit.dt | luxon.diffForHumans }}</b-badge></b-list-group-item>
                </b-list-group>
            </b-col>
            <b-col sm="9">
                <b-row  align-h="end">
                    <b-col sm="2" v-if="own">
                        <b-button type="button" :to="{ name: 'settings'}" :block="true" variant="secondary">Edit</b-button>
                    </b-col>
                    <b-col v-if="!own" sm="2">x
                        <b-button type="button" @click="like" v-if="!user.i_like" :block="true" variant="danger">I LIKE</b-button>
                        <b-button type="button" @click="unlike" v-else block variant="warning">UNLIKE</b-button>
                    </b-col>
                    <b-col  v-if="!own" sm="3">
                        <b-button type="button" v-if="!user.i_report" @click="report" block>Report</b-button>
                        <b-button type="button" v-else diseable block>Already reported</b-button>
                    </b-col>
                    <b-col  v-if="!own" sm="2">
                        <b-button type="button" v-if="!user.i_block" @click="block"  variant="outline-danger" block>Block</b-button>
                        <b-button type="button" v-else @click="unblock" variant="outline-danger" block>Unblock</b-button>
                    </b-col>
                </b-row>
                <div class="card mt-3">
                    <div class="card-body">
                        <h1>{{ user.username }} <b-badge class="float-right" variant="primary">{{ user.gender }} <b-badge variant="light">{{ user.sexual_orientation }}</b-badge></b-badge></h1>
                        <h3>{{ user.first_name }} {{ user.last_name }}</h3>
                        <p> {{ user.description }}</p>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h3>Tags</h3>
                        <b-badge pill variant="info" :key="tag.id" v-for="tag in user.tags">#{{ tag.name }}</b-badge>
                    </div>
                </div>
               <b-row class="mt-3">
                   <b-col v-for="image in user.images" :key="image.name" sm="3">
                       <b-card overlay
                               :border-variant="image.main ? 'primary' : 'secondary'"
                               :img-src="'/storage/avatars/' + image.name"
                               img-alt="Image"
                               img-top
                               class="">
                       </b-card>
                   </b-col>
               </b-row>
            </b-col>
        </b-row>
        <b-row class="mt-3" v-if="own">
            <b-col sm="12">
                <suggestions :userId="user.id"></suggestions>
            </b-col>
        </b-row>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import { suggestions } from '../Suggestions'
    import Suggestions from "../Suggestions.vue";

    export default {
        components: {Suggestions},
        name: "profile",
        component: {
            suggestions
        },
        data() {
          return {
              user: null
          }
        },
        created() {
          this.getUser();
        },
        computed: {
            ...mapGetters(['currentUser']),
            avatar() {
                if (this.user.images.length) {
                    return _.find(this.user.images, { 'main': 1 })
                }
                return null
            },
            own() {
                if (this.currentUser && this.user && this.currentUser === this.user.id) {
                    return true
                }
                return false;
            }
        },
        watch: {
            '$route' (to, from) {
                this.getUser(to.params.id)
            }
        },
        methods: {
            getUser(id = null) {
                if (!id) {
                    id = this.$route.name === 'profile' ? this.currentUser : this.$route.params.id;
                }

                if (id) {
                    axios.get(`api/user/${id}`).then((rep) => {
                        if (rep.status === 200) {
                            this.user = rep.data.user
                        }
                    })
                }
            },
            like() {
                if (!this.own && this.currentUser) {
                    axios.post(`api/like/${this.user.id}`).then((rep) => {
                        if (rep.status === 200) {
                            this.user.i_like = true
                        }
                    })
                }
            },
            unlike() {
                if (!this.own && this.currentUser) {
                    axios.post(`api/unlike/${this.user.id}`).then((rep) => {
                        if (rep.status === 200) {
                            this.user.i_like = false
                        }
                    })
                }
            },
            report() {
                if (!this.own && this.currentUser) {
                    axios.post(`api/report/${this.user.id}`).then((rep) => {
                        if (rep.status === 200) {
                            this.user.i_report = true
                        }
                    })
                }
            },
            block() {
                if (!this.own && this.currentUser) {
                    axios.post(`api/block/${this.user.id}`).then((rep) => {
                        if (rep.status === 200) {
                            this.user.i_block = true
                        }
                    })
                }
            },
            unblock() {
                if (!this.own && this.currentUser) {
                    axios.post(`api/unblock/${this.user.id}`).then((rep) => {
                        if (rep.status === 200) {
                            this.user.i_block = false
                        }
                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>