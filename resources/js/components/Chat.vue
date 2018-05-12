<template>
    <div v-if="jwt">
        <div v-show="display" id="chat">
            <b-row no-gutters>
                <div class="col">
                    <div class="matches">
                        <h3 class="text-center">Your matches</h3>
                        <b-button block size="sm" @click="setRoom(match)" :key="match.id" v-for="match in matches">{{ match.user1_id !== current ? match.user1.username : match.user2.username }}</b-button>
                    </div>
                </div>
                <div class="col">
                    <room v-if="room"  :room="room"></room>
                    <p v-else class="text-center">Select a user on the left</p>
                </div>
            </b-row>
        </div>
        <button @click="display = !display" id="button-chat">Chat</button>
    </div>
</template>

<script>
    import {mapState} from 'vuex'
    import ChatRoom from './ChatRoom'

    export default {
        name: "Chat",
        data() {
            return {
                display: true,
                current: null,
                matches: [],
                room: null
            }
        },
        mounted() {
           if (this.jwt) {
               this.getMatches();
               setInterval(() => {
                   this.getMatches()
               }, 5500)
           }
        },
        components: {
            room: ChatRoom
        },
        computed: {
            ...mapState([
                'jwt',
            ]),
        },
        methods: {
            setRoom(match) {
                this.room = match
            },
            getMatches() {
                axios.get('api/matches').then((rep) => {
                    if (rep.status === 200) {
                        this.current = rep.data.current;
                        this.matches = rep.data.matches;

                        if (this.room)
                            this.room = _.find(this.matches, {id: this.room.id })
                    }
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    #chat {
        z-index: 500;
        background: white;
        position: fixed;
        bottom: 0;
        right: 200px;

        width: 600px;

        border-radius: 5px 5px 0 0;
        border: 1px solid #6f42c1;
        box-shadow: 0 -1px 6px 0 rgba(0,0,0,.5);
        .matches {
            padding: 1rem;
            h3 {
                color: #5e14ff;
            }
            height: 400px;
            border-right: 1px dashed #5e14ff;
        }
    }

    #button-chat {
        position: fixed;
        bottom: 20px;
        right: 20px;

        width: 100px;
        height: 50px;
        border-radius: 20px;

        color: white;

        background: #6f42c1;
        border: 1px dashed #5e14ff;
        box-shadow: 0 2px 8px 0 rgba(0,0,0,.5);
    }
</style>