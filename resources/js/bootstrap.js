/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
// import { forwardRef } from 'react';
window.Pusher = Pusher;


// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'local',
//     cluster: 'mt1',
//     wsHost: location.hostname,
//     wsPort: 443,
//     wssPort: 443,
//     // forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     forceTLS: false,
//     enabledTransports: ['ws', 'wss'],
//     disableStats: true,
// });


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',
    cluster: 'mt1',
    wsHost: "foodyari.com",
    wsPort: 443,
    wssPort: 443,
    // forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});


// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'local',
//     cluster: 'mt1',
//     wsHost: location.hostname,
//     wsPort: 6001,
//     wssPort: 6001,
//     // forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     forceTLS: false,
//     enabledTransports: ['ws', 'wss'],
//     disableStats: true,
// });

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'local', // your PUSHER_APP_KEY
//     cluster: 'mt1',
//     wsHost: window.location.hostname === 'localhost' ? '127.0.0.1' : window.location.hostname,
//     wsPort: 6001,
//     wssPort: 6001,
//     forceTLS: false,
//     encrypted: false,
//     disableStats: true,
//     enabledTransports: ['ws', 'wss'],
//     withCredentials: false,
//     authEndpoint: '/foodyari_live/broadcasting/auth',
//     auth: {
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//             // pass a custom header to tell backend which guard to use
//             'X-Auth-Guard': 'admin', // or 'vendor' or 'web' depending on your frontend context
//             'X-Requested-With': 'XMLHttpRequest'

//         }

//     }
// });

// const echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'local',
//     cluster: 'mt1',
//     // wsHost: window.location.hostname,
//     wsHost: "https://www.foodyari.com/",
//     wsPort: 443,
//     wssPort: 443,
//     forceTLS: true,
//     encrypted: true,
//     disableStats: true,
//     enabledTransports: ['ws', 'wss'],
//     withCredentials: false,
//     authEndpoint: '/broadcasting/auth',
//     auth: {
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//             // pass a custom header to tell backend which guard to use
//             'X-Auth-Guard': 'admin', // or 'vendor' or 'web' depending on your frontend context
//             'X-Requested-With': 'XMLHttpRequest'
//         }
//     },
//     // **Important:**
//     // Set the path where WebSocket proxy will listen
//     // Usually /app or /ws or /socket
//     // Match with LiteSpeed config below!
//     path: '/app',
// });

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY ?? 'local',
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     // wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsHost: foodyari.com,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
//     // forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     forceTLS: false,

//     enabledTransports: ['ws', 'wss'],
//     disableStats: true,
// });
