<template>
  <v-card
    class="overflow-hidden"
    color="grey darken-1"
    dark
  >
    <v-toolbar
      flat
      color="grey darken-3"
    >
      <v-icon class="mr-1">
        {{ icons.mdiAccountCog }}
      </v-icon>
      <v-toolbar-title class="font-weight-light">
        Профиль
      </v-toolbar-title>
      <v-spacer />
      <v-btn
        color="grey darken-1"
        fab
        small
        @click="isEditing = !isEditing"
      >
        <v-icon v-if="isEditing">
          {{ icons.mdiClose }}
        </v-icon>
        <v-icon v-else>
          {{ icons.mdiPencil }}
        </v-icon>
      </v-btn>
    </v-toolbar>
    <v-card-text>
      <v-form
        ref="form"
        v-model="valid"
      >
        <v-text-field
          v-model="name"
          :disabled="!isEditing"
          :counter="16"
          :rules="nameRules"
          color="white"
          class="white--text"
          label="Логин"
          clearable
        />
        <v-text-field
          v-model="email"
          :disabled="!isEditing"
          :rules="emailRules"
          color="white"
          label="E-mail"
          clearable
        />
      </v-form>
    </v-card-text>
    <v-divider />
    <v-card-actions>
      <v-spacer />
      <v-btn
        :disabled="!isDisabled"
        color="grey darken-3"
        @click="validate(); save()"
      >
        Сохранить
      </v-btn>
    </v-card-actions>
    <v-snackbar
      v-model="hasSaved"
      :timeout="2000"
      absolute
      bottom
      left
    >
      {{ saveMsg }}
    </v-snackbar>
  </v-card>
</template>

<script>
import { mdiAccountCog, mdiClose, mdiPencil } from '@mdi/js'
import { mapGetters } from 'vuex'
export default {
  data () {
    return {
      valid: true,
      name: '',
      nameRules: [
        v => !!v || 'Логин обязателен',
        v => (v && v.length <= 16) || 'Логин должен содержать не более 16 символов.',
        v => (v && v.length >= 3) || 'Логин должен содержать не менее 3 символов.'
      ],
      email: '',
      emailRules: [
        v => !!v || 'E-mail обязателен',
        v => /.+@.+\..+/.test(v) || 'E-mail должен быть валидным.'
      ],
      icons: {
        mdiAccountCog,
        mdiClose,
        mdiPencil
      },
      hasSaved: false,
      isEditing: null,
      model: null,
      saveMsg: 'Ваш профиль обновлен.'
    }
  },
  computed: {
    isDisabled () {
      return this.isEditing && this.valid
    },
    ...mapGetters({
      user: 'auth/user'
    })
  },

  created () {
    this.name = this.user['name']
    this.email = this.user['email']
  },

  methods: {
    validate () {
      this.$refs.form.validate()
    },
    async save () {
      this.isEditing = !this.isEditing
      const { data } = await this.$http.patch('/api/settings/profile', { name: this.name, email: this.email })
      if (data) {
        this.saveMsg = 'Ваш профиль обновлен.'
        this.$store.dispatch('auth/updateUser', { user: data })
        this.hasSaved = true
      } else {
        this.saveMsg = 'Произошла ошибка при обновлении профиля.'
        this.hasSaved = true
      }
    }
  }
}
</script>
