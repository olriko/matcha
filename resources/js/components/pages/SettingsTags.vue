<template>
    <div class="card mt-5">
        <div class="card-body">
            <h5 class="card-title">Tags</h5>
            <b-button-group class="mr-1 mt-1" size="sm" :key="tag.id" v-for="tag in tags">
                <b-button>{{ tag.name }}</b-button>
                <b-button variant="danger" @click="removeTag(tag)">x</b-button>
            </b-button-group>
            <hr>
            <b-form-group>
                <b-input-group prepend="Add a tag">
                    <b-form-input @input="search" v-model="toSearch" type="text"></b-form-input>
                    <b-input-group-append>
                        <b-btn @click="addTag(toSearch)" variant="success">+</b-btn>
                    </b-input-group-append>
                </b-input-group>
            </b-form-group>
            <b-button-group class="mr-1 mt-1" size="sm" :key="'add-' + tag.id" v-for="tag in results">
                <b-button>{{ tag.name }}</b-button>
                <b-button variant="success" @click="addTag(tag.name)">+</b-button>
            </b-button-group>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SettingsTags",
        props: {
            tagsInit: Array,
        },
        mounted() {
            this.tags = this.tagsInit
        },
        data() {
            return {
                tags: [],
                toSearch: '',
                results: []
            }
        },
        methods: {
            search() {
                if (this.toSearch) {
                    axios.get(`api/tags/${this.toSearch}`).then((rep) => {
                        if (rep.status === 200) {
                            this.results =  _.differenceWith(rep.data.tags, this.tags, _.isEqual);
                        }
                    })
                }
            },
            removeTag(tag) {
                axios.delete(`api/tag/${tag.id}`).then((rep) => {
                    if (rep.status === 200) {
                        this.tags = _.filter(this.tags, (o) => {
                            return o.id !== tag.id
                        })
                    }
                })
            },
            addTag(tag) {
                axios.post(`api/tag`, {tag: tag}).then((rep) => {
                    if (rep.status === 200) {
                        this.tags.push(rep.data.tag)
                        this.results = [];
                        this.toSearch = '';
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>