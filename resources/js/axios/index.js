import axios from 'axios'

// Axios Config
axios.defaults.baseURL = "http://weather.smshaju.com/api/";
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["Content-Type"] = 'multipart/form-data';
axios.defaults.withCredentials = true;
axios.defaults.headers.common["Authorization"] = "Bearer "+ localStorage.getItem("access_token");

export default axios;