import axios from 'axios';
window.axios = axios;

// Axios-related things
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Bootstrap popover
$(function () {
    $('[data-toggle="popover"]').popover()
});