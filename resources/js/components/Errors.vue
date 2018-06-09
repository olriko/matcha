<template>
    <div id="error">
        <b-alert v-if="errors.length > 0" variant="danger" @dismissed="empty" dismissible show>
            <h3>Error(s)</h3>
            <ul>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
        </b-alert>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        name: "errors",
        computed: {
            ...mapGetters([
                'errors',
            ])
        },
        methods: {
            empty() {
                this.$store.commit('errors')
            }
        }
    }
</script>

<style scoped>
    #error {
        position: fixed;
        bottom: 10px;
        right: 10px;
        max-width: 500px;
        z-index: 999;
    }
</style>