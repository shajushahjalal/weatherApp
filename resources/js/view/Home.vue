<template>
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">Weather APP</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4">
                                <label>Select Country</label>
                                <select v-model="country_id" v-on:change="loadCity" class="form-control">
                                    <option value="" disabled >Select Country</option>
                                    <option v-for="country in country_list_arr" :value="country.id" >{{ country.name }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label>Select City</label>
                                <select v-model="city_id" v-on:change="changeCity" class="form-control">
                                    <option value="" disabled >Select City</option>
                                    <option v-for="city in city_list_arr" :value="city.id">{{ city.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12">
                                <h3 class="text-primary">Last Weather Update</h3>
                            </div>
                            <div class="col-12 mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Country</th>
                                            <th>City</th>
                                            <th>Weather Condition</th>
                                            <th>Temperature</th>
                                            <th>Temperature Feels</th>
                                            <th>Humidity</th>
                                            <th>Wind_speed</th>
                                            <th>Updated Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="_weather in weather_data.data">
                                            <td>{{ capitalized(_weather.country)  }}</td>
                                            <td>{{ capitalized(_weather.city_name)  }}</td>
                                            <td>{{ capitalized(_weather.weather_condition)  }}</td>
                                            <td>{{ _weather.temp_celsius }} <sup>o</sup>C </td>
                                            <td>{{ _weather.temp_feels }} <sup>o</sup>C </td>
                                            <td>{{ _weather.humidity }}% </td>
                                            <td>{{  _weather.wind_speed  }} Km/h</td>
                                            <td>{{ convertDate(_weather.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                </table>                                
                            </div>
                            <div class="col-12">
                                <Bootstrap5Pagination
                                    :data="weather_data"
                                    @pagination-change-page="loadWeather"
                                />
                            </div>
                        </div>

                        <!-- <div class="row mt-5">
                            <GMapMap
                                :center="center"
                                :zoom="10"
                                map-type-id="terrain"
                                style="width: 100%; height: 500px" 
                            >
                                <GMapCluster>
                                <GMapMarker
                                    :key="index"
                                    v-for="(m, index) in markers"
                                    :position="m.position"
                                    :clickable="true"
                                    :draggable="true"
                                    @click="center=m.position"
                                />
                                </GMapCluster>
                            </GMapMap>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="col-12"></div>
        </div>
    </div>
</template>

<script>
    import {country_list, city_list, weather} from '../routes/Api';
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';


    import moment from 'moment';

    export default{
        components:{
            Bootstrap5Pagination
        },
        data(){
            return {
                country_list_arr: [],
                city_list_arr   : [],
                weather_data    : {},
                country_id      : "",
                city_id         : "",

                center: {lat: 51.093048, lng: 6.842120},
                markers: [
                ]
            }
        },
        mounted() {
            this.loadWeather();
            this.loadCountry();
        },
        created : function(){
            const loadData = setInterval(() => {
                this.loadWeather();
            }, 600000);
        },
        methods : {
            loadCountry(){
                this.axios.get(country_list).then((response) => {
                    if(response.data.status){
                        this.country_list_arr = response.data.countries;
                    }else{
                        console.log(response.data.message);
                    }
                })
            },

            loadCity(){
                this.axios.get(city_list + "?country_id=" + this.country_id).then((response) => {
                    if(response.data.status){
                        this.city_list_arr = response.data.cities;
                    }else{
                        console.log(response.data.message);
                    }
                });
                this.loadWeather();
            },
            changeCity(){
                this.loadWeather();
                let city = this.city_list_arr.filter( (list, index) => {
                    if(list.id == this.city_id){
                        this.center.lat = list.lat;
                        this.center.lon = list.lon;
                    }
                });
            },
            loadWeather(page=1){
                this.axios.get(weather+"?page="+ page +"&city_id=" + this.city_id + "&country_id=" + this.country_id).then((response) => {
                    if(response.data.status){
                        this.weather_data = response.data.weithers;
                    }else{
                        console.log(response.data.message);
                    }
                });
            },
            capitalized(name) {
                const capitalizedFirst = name[0].toUpperCase();
                const rest = name.slice(1);
                return capitalizedFirst + rest;
            },
            convertDate(date){               
                return moment(String(date)).format('DD/mm/YY hh:mm ')
            }

        },
    }
</script>