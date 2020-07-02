import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'
import vuetify from '~/plugins/vuetify'
import axios from 'axios'
import '~/plugins'
import '~/components'

Vue.prototype.$http = axios
Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  vuetify,
  axios,
  ...App
})
