// public/js/webcam.js

import Echo from "laravel-echo";

// กำหนด URL ของ Laravel Echo server ในไฟล์ bootstrap.js หรือ main.js
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
});
