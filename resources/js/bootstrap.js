import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Setup axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Hardcoded Pusher setup
// console.log("Pusher key:", "7512175880e49640d2d8");

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "7512175880e49640d2d8",
    cluster: "ap1",
    forceTLS: true,
    wsHost: "ws-ap1.pusher.com",
    wsPort: 443,
    wssPort: 443,
    enabledTransports: ['ws', 'wss'],
     auth: {
        headers: {
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        }
    }
});

window.Echo.connector.pusher.connection.bind('error', function(err) {
    console.error('Pusher connection error:', err);
});


// const userId = document.head.querySelector('meta[name="user-id"]').content;


// // Listen to the private channel for this user.
// window.Echo.private(`users.${userId}`)
//     .listen('MessageSent', (event) => {
//         console.log('Received message:', event);
//     });


