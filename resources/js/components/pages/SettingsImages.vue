<template>
    <div class="card mt-5">
        <div class="card-body">
            <h5 class="card-title">Avatars</h5>
            <b-row>
                <b-col v-for="image in images" :key="image.id" sm="6">
                    <b-card overlay
                            :border-variant="image.main ? 'primary' : 'secondary'"
                            :img-src="'/storage/avatars/' + image.name"
                            img-alt="Image"
                            img-top
                            class="mb-2">
                        <b-button-group v-if="!image.main" size="sm">
                            <b-button @click="deleteImage(image)" variant="danger">Delete</b-button>
                            <b-button @click="setMain(image)" variant="warning">Main</b-button>
                        </b-button-group>
                    </b-card>
                </b-col>
            </b-row>
            <b-form-file v-show="images.length < 5" v-model="image" accept="image/*" @input="addImage(image)" placeholder="Choose a file..."></b-form-file>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SettingsImages",
        props: {
            pictures: Array,
        },
        created() {
            this.images = this.pictures
        },
        data() {
          return {
              image: null,
              images: []
          }
        },
        methods: {
            deleteImage(image) {
                axios.delete(`api/image/${image.id}`).then((rep) => {
                    if (rep.status === 200) {
                        this.images = _.filter(this.images, (o) => {
                            return o.id !== image.id
                        })
                    }
                })
            },
            setMain(image) {
                axios.put(`api/image/${image.id}`).then((rep) => {
                    if (rep.status === 200) {
                        this.images = _.map(this.images, (o) => {
                            if (o.id === image.id) {
                                o.main = true
                            } else {
                                o.main = false
                            }
                            return o
                        })
                    }
                })
            },
            addImage(image) {
                if (image) {
                    console.log(image);

                    let data = new FormData();
                    data.append('image', image, image.name);

                    axios.post(`api/image`, data, {
                        headers: { 'Content-type': "multipart/form-data;  charset=utf-8; boundary=" + Math.random().toString().substr(2) + ";" }
                    }).then((rep) => {
                        if (rep.status === 200 && rep.data.success === true) {
                            this.images.push(rep.data.image);
                        }
                        this.image = null;
                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>