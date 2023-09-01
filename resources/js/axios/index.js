import axios from 'axios'

// Axios Config
axios.defaults.baseURL = "http://127.0.0.1:8000/api/";
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["Content-Type"] = 'multipart/form-data';
axios.defaults.withCredentials = true;
axios.defaults.headers.common["Authorization"] = "Bearer "+ localStorage.getItem("access_token");

export default axios;