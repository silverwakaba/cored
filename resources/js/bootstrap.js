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

// Trivia
console.log('As it is, as it is, without change', 'そのまんま そのまんま そのまま');
console.log('Its always been the same, so lets go back to sleep', 'ずっとそのまま 寝ていてね');
console.log('Have a good night, have a good night, have a good night', 'おやすみよ おやすみよ おやすみよ');
console.log('Ah, at last, its finally gone', 'ああ やっと終わるんだ');
