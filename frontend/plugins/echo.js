import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: 'pusher_key_123',
  wsHost: '127.0.0.1', // sesuaikan ini sesuai ip server-nya
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
});
