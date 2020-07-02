<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="miniVariant"
      :clipped="clipped"
      color="grey lighten-3"
      fixed
      app
    >
      <v-list>
        <v-list-item
          v-for="(item, i) in items"
          :key="i"
          :to="item.to"
          router
          exact
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-text="item.title" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar
      :clipped-left="clipped"
      color="grey darken-3"
      elevation="3"
      fixed
      app
    >
      <v-app-bar-nav-icon class="white--text" @click.stop="drawer = !drawer" />
      <v-toolbar-title class="white--text" v-text="title" />
      <v-spacer />
      <v-btn icon class="white--text">
        <v-menu bottom transition="slide-y-transition">
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              dark
              icon
              v-bind="attrs"
              v-on="on"
              @click="openMenu()"
            >
              <v-icon>{{ mdiDotsVertical }}</v-icon>
            </v-btn>
          </template>

          <v-list>
            <v-list-item
              v-for="(item, i) in dotsMenu"
              :key="i"
              :to="item.to"
              router
            >
              <v-icon class="pr-2">
                {{ item.icon }}
              </v-icon>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
              <v-divider />
            </v-list-item>
          </v-list>
        </v-menu>
      </v-btn>
    </v-app-bar>
    <v-content>
      <v-container>
        <child />
      </v-container>
    </v-content>
    <v-footer :fixed="fixed" color="grey darken-3" app absolute>
      <v-col
        class="text-center white--text"
        cols="12"
      >
        {{ new Date().getFullYear() }} — <strong>Пынячерт</strong>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
import store from '~/store'
import { mdiApps, mdiChartBubble, mdiDotsVertical, mdiAccountPlus, mdiAccountCheck, mdiAccountMinus, mdiAccount } from '@mdi/js'
export default {
  data () {
    return {
      clipped: false,
      drawer: false,
      fixed: false,
      mdiDotsVertical,
      items: [
        {
          icon: mdiApps,
          title: 'Главная',
          to: '/'
        },
        {
          icon: mdiChartBubble,
          title: 'Вордпресс',
          to: '/inspir'
        }
      ],
      dotsMenu: '',
      miniVariant: false,
      title: 'SAMOSBOR-wiki'
    }
  },
  methods: {
    openMenu () {
      this.dotsMenu = [
        store.getters['auth/token'] ? '' : {
          title: 'Регистрация',
          icon: mdiAccountPlus,
          to: '/sign-up'
        },
        store.getters['auth/token'] ? '' : {
          title: 'Вход',
          icon: mdiAccountCheck,
          to: '/sign-in'
        },
        store.getters['auth/token'] ? {
          title: 'Профиль',
          icon: mdiAccount,
          to: '/settings/profile'
        } : '',
        store.getters['auth/token'] ? {
          title: 'Выход',
          icon: mdiAccountMinus,
          to: '/logout'
        } : ''
      ].filter(e => typeof e !== 'string')
    }
  }
}
</script>
