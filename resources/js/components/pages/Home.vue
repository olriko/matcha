<template>
    <div class="mt-3">
        <b-row>
            <b-col sm="7">
                <b-jumbotron header-tag="h3" bg-variant="info" text-variant="white" header="Matchaaaaaaaaa"
                             lead="A easy way to find love <3">
                    <p> An annoying 42 project.. </p>
                </b-jumbotron>
            </b-col>
            <b-col sm="5">
                <div class="card">
                    <div class="card-body">
                        <h4>Search</h4>
                        <b-row class="my-1 mt-5">
                            <b-col sm="2"><label>Age</label></b-col>
                            <b-col sm="10">
                                <vue-slider tooltip="always" :min="18" :max="99" v-model="search.age"></vue-slider>
                            </b-col>
                        </b-row>
                        <b-row class="my-1 mt-4">
                            <b-col sm="2"><label>Score</label></b-col>
                            <b-col sm="10">
                                <vue-slider tooltip="always" :interval="50" v-model="search.score" :min="-1000"
                                            :max="3000"></vue-slider>
                            </b-col>
                        </b-row>
                        <b-row class="my-1 mt-4">
                            <b-col sm="2"><label>Tags</label></b-col>
                            <b-col sm="10">
                                <vue-tags-input
                                        v-model="tag"
                                        :add-only-from-autocomplete="true"
                                        :tags="search.tags"
                                        @tags-changed="updateTags"
                                        :autocomplete-items="suggestions">
                                </vue-tags-input>
                            </b-col>
                        </b-row>
                        <b-row class="my-1 mt-4">
                            <b-col sm="2"><label>Localization</label></b-col>
                            <b-col sm="10">
                            </b-col>
                        </b-row>
                    </div>
                </div>
            </b-col>
        </b-row>
        <b-row class="mt-3" align-h="center">
            <b-col sm="3">

            </b-col>
        </b-row>
    </div>
</template>

<script>
    import vueSlider from 'vue-slider-component'
    import VueTagsInput from '@johmun/vue-tags-input';


    export default {
        name: "home",
        components: {
            vueSlider,
            VueTagsInput
        },
        created() {

        },
        watch: {
            'tag': 'fetchTags',
        },
        data() {
            return {
                search: {
                    age: [18, 60],
                    score: [0, 2000],
                    tags: [],
                    localization: {}
                },
                results: [],
                tag: '',
                suggestions: []
            }
        },
        methods: {
            queryOfDeath() {
                axios.post('api/search', this.search).then((rep) => {
                    if (rep.status === 200) {
                        this.results = rep.data.results;
                    }
                })
            },
            updateTags(newTags) {
                this.search.tags = newTags;
                this.suggestions = [];
            },
            fetchTags() {
                if (this.tag.length > 0) {
                    clearTimeout(this.debounce);
                    this.debounce = setTimeout(() => {
                        axios.get(`api/tags/${this.tag}`).then((rep) => {
                            if (rep.status === 200) {
                                this.suggestions =  rep.data.tags.map(o => {
                                    return {text: o.name, id: o.id}
                                });
                            }
                        }).catch(() => console.warn('Oh. Something went wrong'));
                    }, 600);
                }
            }
        }
    }
</script>

<style scoped>

</style>