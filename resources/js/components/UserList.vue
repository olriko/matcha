<template>
    <div class="row">
        <b-col sm="12" md="6" lg="4">
            <label>
                <input v-on:click="sortBy('birthday')" v-bind:checked="sortType === 'birthday'" type="radio" name="sort" />
                sort by age
            </label>
        </b-col>
        <b-col sm="12" md="6" lg="4">
            <label>
                <input v-on:click="sortBy('distance')" v-bind:checked="sortType === 'distance'" type="radio" name="sort" />
                sort by distance
            </label>
        </b-col>
        <b-col sm="12" md="6" lg="4">
            <label>
                <input v-on:click="sortBy('score')" v-bind:checked="sortType === 'score'" type="radio" name="sort" />
                sort by popularity
            </label>
        </b-col>
        <b-col v-for="user in displayedList" :key="user.id" sm="6" md="4" lg="3">
            <user-card :user="user"></user-card>
        </b-col>
        <div class="col-sm-12">
            <div class="pagination">
                <b-button size="sm" @click="previousPage()">Previous</b-button>
                <b-badge>{{ page }}</b-badge>
                <b-button size="sm" @click="nextPage()">Next</b-button>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import userCard from './UserCard'

    export default {
        name: "user-list",
        props: {
            list: Array
        },
        components: {
            'user-card': userCard
        },
        computed: {
            displayedList() {
                const from = (this.page - 1) * 30;
                const to = (this.page * 30 ) - 1;
                
                console.log('this.list[0]',  this.list[0]);
                return this.list
                    .sort((a, b) => a[this.sortType] < b[this.sortType] ? -1 : 1)
                    .map(u => {
                        u.distance = +u.distance.toFixed(1);
                        return u;
                    })
                    .slice(from, to);
            }
        },
        data() {
            return {
                page: 1,
                sortType: null,
            };
        },
        methods: {
            nextPage() {
                if (this.list.length - (this.page * 30) >= 0) {
                    this.page++;
                }
            },
            previousPage() {
                if (this.page > 1) {
                    this.page--;
                }
            },
            sortBy(type) {
                this.sortType = type;
            }
        }
    }

</script>

<style type="scss" scoped>

    .pagination {
        padding: 1rem;
        display: flex;
        justify-content: center;
        align-items: center;

        span {
            margin: 0.5rem;
        }
    }

</style>
