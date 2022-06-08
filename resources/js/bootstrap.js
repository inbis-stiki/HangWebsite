window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

function modal(modalID, id, callback) {
    if (typeof id !== 'undefined') {
        // id nya untuk id item dari data
        // masukin function nya pakai callback ini biar bisa flexibel
        callback(id);
    }
    let modalid = document.getElementById(modalID)
    modalid.classList.toggle('hidden');
    let modalcontent = document.getElementById(modalID + '-content')
    modalcontent.classList.toggle('hidden');
}

window.modal = modal;
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
