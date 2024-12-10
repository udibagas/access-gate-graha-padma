import Echo from 'laravel-echo'

window.Pusher = require('pusher-js')

window.echo = new Echo({
  broadcaster: 'reverb',
  key: 'g2iix2c1zwlt3dgvo35h',
  wsHost: '127.0.0.1', // sesuaikan ini sesuai ip server-nya
  wsPort: 8080,
  forceTLS: false,
  disableStats: true,
  authorizer: (channel, options) => {
    return {
      authorize: (socketId, callback) => {
        api('/api/broadcasting/auth', {
          method: 'POST',
          body: JSON.stringify({
            socket_id: socketId,
            channel_name: channel.name,
          }),
        })
          .then((response) => callback(false, response))
          .catch((error) => callback(true, error))
      },
    }
  },
})
