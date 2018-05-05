<template>
    <div v-if="jwt">
        <div v-show="display" id="chat">
            <div class="row">
                <div class="col">
                    <div class="matches">
                        <h3 class="text-center">Your matches</h3>

                    </div>
                </div>
                <div class="col"></div>
            </div>
        </div>
        <button @click="display = !display" id="button-chat">Chat</button>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "Chat",
        data() {
            return {
                display: true,
                current: null,
                matches: []
            }
        },
        mounted() {
           if (this.jwt) {
               this.getMessages();
           }
        },
        computed: {
            ...mapState([
                'jwt',
            ]),
        },
        methods: {
            getMessages() {
                axios.get('api/matches').then((rep) => {
                    if (rep.status === 200) {
                        this.current = rep.data.current;
                        this.messages = rep.data.messages;
                        this.matches = rep.data.matches;
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
        padding: 1rem;

        .matches {
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