import './bootstrap';
import Alpine from 'alpinejs';

// if (window.Alpine) {
//     console.warn("Alpine.js is already loaded.");
// } else {
// window.Alpine = Alpine;
// Alpine.start();
// }

// const userId = window.userId; // Misalnya, dari Blade: @json(auth()->id())

const userId = document.head.querySelector('meta[name="user-id"]').content;

window.Echo.private(`users.${userId}`)
    .listen('MessageSent', (event) => {
        console.log('Received message:', event);
    });

    // console.log('User ID:', userId);

