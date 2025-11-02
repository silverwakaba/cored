// import axios from 'axios';
// window.axios = axios;

// // Axios-related things
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

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

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

// import './echo';
