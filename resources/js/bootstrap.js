import axios from 'axios';
window.axios = axios;

// Axios-related things
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Bootstrap-related
$(function () {
    $('[data-toggle="popover"]').popover();

    $('.select2bs4').select2({
        width: '100%',
        theme: 'bootstrap4',
    });
});