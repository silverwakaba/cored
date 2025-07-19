import axios from 'axios';
window.axios = axios;

// Axios-related things
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Bootstrap-related
$(function () {
    $('[data-toggle="popover"]').popover();

    $('.select2bs4').select2({
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4',
        placeholder: 'Select an Option...',
    });
});