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
        {{ icons.mdiAccountLock }}
      </v-icon>
      <v-toolbar-title class="font-weight-light">
        Пароль
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
          v-model="password"
          :disabled="!isEditing"
          :rules="passRules"
          color="white"
          class="white--text"
          label="Новый пароль"
          clearable
        />
        <v-text-field
          v-model="password_confirmation"
          :disabled="!isEditing"
          :rules="passRules"
          color="white"
          label="Подтвердите пароль"
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
        сменить пароль
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
import { mdiAccountLock, mdiClose, mdiPencil } from '@mdi/js'
export default {
  data () {
    return {
      valid: true,
      password: '',
      password_confirmation: '',
      passRules: [
        v => !!v || 'Пароль обязателен',
        v => (v && v.length >= 6) || 'Пароль должен быть больше 6 символов.',
        v => (v && v.length < 32) || 'Пароль должен быть не больше 32 символов.'
      ],
      icons: {
        mdiAccountLock,
        mdiClose,
        mdiPencil
      },
      hasSaved: false,
      isEditing: null,
      model: null,
      saveMsg: 'Ваш пароль обновлен.'
    }
  },
  computed: {
    isDisabled () {
      return this.isEditing && this.valid && (this.password === this.password_confirmation)
    }
  },
  methods: {
    validate () {
      this.$refs.form.validate()
    },
    resetValidation () {
      this.$refs.form.resetValidation()
    },
    async save () {
      this.isEditing = !this.isEditing
      await this.$http.patch('/api/settings/password', { password: this.password, password_confirmation: this.password_confirmation })
        .catch(e => {
          this.saveMsg = 'Произошла ошибка при смене пароля.'
          this.hasSaved = true
        })
      this.saveMsg = 'Ваш пароль обновлен.'
      this.hasSaved = true

      this.resetValidation()
    }
  }
}
</script>
