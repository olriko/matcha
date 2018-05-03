<template>
    <div class="card mt-5">
        <div class="card-body">
            <h4>Location</h4>
            <label>Change manualy</label>
            <gmap-autocomplete :enable-geolocation="true" :value="formatted_address" :componentRestrictions="{country: 'fr'}"
                               region="FR" @place_changed="setPlace" :options="options"
                               class="form-control"></gmap-autocomplete>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SettingsLocation",
        props: {},
        data() {
            return {
                formatted_address: '',
                place: null,
                position: null,
                options: {
                    language: 'fr'
                }
            }
        },
        methods: {
            setPlace(place) {
                this.position = {lat: place.geometry.location.lat(), lng: place.geometry.location.lng()};
                this.place = place
                this.formatted_address = place.formatted_address
                axios.put('/api/user/geo', this.position).then(() => {})
            }
        }
    }
</script>

<style scoped>

</style>