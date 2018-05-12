<template>
    <div class="room">
        <div class="head">
            <div class="len">{{ room.messages.length }}</div>
            {{ her.username }}
            <div class="close" @click="close">x</div>
        </div>
        <div class="messages" ref="scroll">
            <div :class="message.user_id === her.id ? 'message her' : 'message me'" v-for="message in room.messages">
                <div class="content">{{ message.content }}</div>
            </div>
        </div>
        <div class="input" @keypress.enter="send()" >
            <b-input-group>
                <b-form-input v-model="input"></b-form-input>
                <b-input-group-append>
                    <b-btn variant="success" v-show="input.length > 2" @click="send()">></b-btn>
                </b-input-group-append>
            </b-input-group>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ChatRoom",
        props: {
            room: Object,
        },
        data() {
            return {
                input: '',
            }
        },
        mounted() {
            this.scroll();
        },
        methods: {
            scroll() {
                this.$nextTick().then(() => {
                    if (this.$refs['scroll']) {
                        this.$refs['scroll'].scrollTop = this.$refs['scroll'].clientHeight + 100000;
                    }
                });
            },
            close() {
                this.$parent.room = null
            },
            send() {
                if (this.input.length > 2) {
                    axios.post(`api/message`, {
                        'match_id': this.room.id,
                        'content': this.input
                    }).then((rep) => {
                        if (rep.status === 200) {
                            this.room.messages.push({
                                'user_id': this.$parent.current,
                                'content': this.input,
                                'match_id': this.room.id
                            });

                            this.input = '';
                            this.scroll();
                        }
                    })
                }
            }
        },
        watch: {
            'room'(to, from) {
                this.scroll();
            }
        },
        computed: {
            me() {
                return this.room.user1_id === this.$parent.current ? this.room.user1 : this.room.user2
            },
            her() {
                return this.room.user1_id !== this.$parent.current ? this.room.user1 : this.room.user2
            }
        },
    }
</script>

<style scoped lang="scss">
    .head {
        width: 100%;
        height: 30px;
        background-color: #6f42c1;
        color: white;
        text-align: center;
        padding: 4px 5px;
    }

    .room {
        position: relative;
        height: 100%;
    }

    .input {
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
    }

    .close {
        float: right;
        color: white;
        cursor: pointer;
    }
    .len {
        float: left;
    }

    .messages {
        overflow-y: scroll;
        height: 330px;
        .message {
            padding: 5px;
            min-height: 50px;

            .content {
                padding: 10px;
            }

            &.me .content {
                float: right;
                background-color: #0c5460;
                color: white;
                border-radius: 5px;
            }

            &.her .content {
                color: white;
                float: left;
                background-color: #603010;
                border-radius: 5px;

            }
        }
    }
</style>