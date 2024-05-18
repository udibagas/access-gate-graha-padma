export default {
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: false,
  target: 'static',

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'ACCESS GATE',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    'bootstrap/dist/css/bootstrap.css',
    'element-ui/lib/theme-chalk/index.css',
    '@/assets/css/app.css'
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    '@/plugins/element-ui',
    '@/plugins/echo'
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/auth-next'
  ],

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // baseURL: process.env.API_URL || 'http://localhost:8000',
    credentials: true
  },

  auth: {
    redirect: {
      login: '/login',
      logout: '/',
      calback: '/login',
      home: '/'
    },
    strategies: {
      laravelSanctum: {
        provider: 'laravel/sanctum',
        url: '/',
        endpoints: {
          login: {
            url: '/api/login',
            method: 'post',
            propertyName: false
          },
          logout: {
            url: '/api/logout',
            method: 'post'
          },
          user: {
            url: '/api/me',
            method: 'get',
            propertyName: false
          },
          csrf: {
            url: '/sanctum/csrf-cookie'
          }
        },
        tokenType: false,
        tokenRequired: false
      }
    }
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    transpile: [/^element-ui/],
  }
}
