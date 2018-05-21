<template>
    <div>
        <router-link :to="{ name: 'user', params: { id: user.id }}">
            <div class="card-container">
                <img v-bind:src="avatar"
                     alt="Avatar"/>
                <div class="card">
                    <h4><b>{{ user.first_name }} {{ user.last_name }}</b>, {{ age }} yo</h4>
                    <h5>{{ user.localization }}km away from you</h5>
                    <h5>score: {{ user.score }}</h5>
                    <p>{{ user.description }}</p>
                </div>
            </div>
        </router-link>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import * as moment from 'moment'

    export default {
        name: "user-card",
        props: {
            user: Object
        },
        computed: {
            age() {
              return moment().diff(moment(this.user.birthday), 'years')
            },
            avatar() {
                return this.user.image
                ? '/storage/avatars/' + this.user.image
                : 'https://www.w3schools.com/howto/img_avatar.png';
            },
        },
    }

</script>

<style type="scss" scoped>

    .card-container {
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;

        &:hover {
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
        }

        .card {
            padding: 2px 16px;
        }
    }

    img {
        max-width: 100%;
        height: auto;
        display: block;
    }

</style>
