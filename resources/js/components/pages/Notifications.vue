<template>
    <b-row>
        <b-col sm="6" offset-sm="3">
            <h1>Notifications</h1>
            <b-button @click="getNotifications" size="sm" variant="info">Refresh</b-button>
            <b-button @click="read" size="sm">read</b-button>
            <b-list-group class="mt-2">
                <b-list-group-item :variant="notification.read ? 'secondary' : 'danger'" :key="notification.id" :to="{name: 'user', params: {id: notification.notify_by }}" v-for="notification in notifications">{{ notification.description }}</b-list-group-item>
            </b-list-group>
            <b-badge>{{ notifications.length }}</b-badge>
        </b-col>
    </b-row>
</template>

<script>
    export default {
        name: "Notifications",
        data() {
            return {
                notifications: []
            }
        },
        mounted() {
            this.getNotifications()
        },
        methods: {
            read() {
                axios.post('api/notifications/read').then((rep) => {
                    if (rep.status === 200) {
                        this.notifications = _.map(this.notifications, (o) => {
                            o.read = 1;
                            return o;
                        })
                    }
                })
            },
            getNotifications() {
                this.notifications = [];
                axios.get('api/notifications', {
                    params: {
                        all: true
                    }
                }).then((rep) => {
                    if (rep.status === 200) {
                        this.notifications = rep.data.notifications
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>