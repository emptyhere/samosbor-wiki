<template>
  <v-container fluid>
    <v-container :style="{ 'max-width' : '600px' }">
      <v-form
        ref="form"
        v-model="valid"
      >
        <v-text-field
          v-model="email"
          :rules="emailRules"
          label="E-mail"
          required
        />

        <v-text-field
          v-model="password"
          :rules="passRules"
          :counter="32"
          label="Пароль"
          required
        />

        <v-btn
          color="grey darken-3"
          class="white--text"
          :disabled="!valid"
          @click="validate; signIn()"
        >
          Вход
        </v-btn>
        <v-btn text @click="reset">
          Отмена
        </v-btn>
      </v-form>

      <v-snackbar
        v-model="snackbar"
        color="grey darken-3"
        bottom
        right
        vertical
      >
        {{ error }}

        <template v-slot:action="{ attrs }">
          <v-btn
            color="white"
            text
            v-bind="attrs"
            @click="snackbar = false"
          >
            Закрыть
          </v-btn>
        </template>
      </v-snackbar>
    </v-container>
  </v-container>
</template>

<script>

export default {
  middleware: 'guest',

  metaInfo () {
    return { title: this.$t('login') }
  },

  data: () => ({
    valid: true,
    email: '',
    emailRules: [
      v => !!v || 'E-mail обязателен',
      v => /.+@.+\..+/.test(v) || 'E-mail должен быть валидным.'
    ],
    password: '',
    passRules: [
      v => !!v || 'Пароль обязателен',
      v => (v && v.length >= 6) || 'Пароль должен быть больше 6 символов.',
      v => (v && v.length < 32) || 'Пароль должен быть не больше 32 символов.'
    ],
    snackbar: false,
    error: ''
  }),

  methods: {
    async signIn () {
      const { data } = await this.$http.post('/api/login', { email: this.email, password: this.password }).catch(e => {
        if (e.response.status === 422) {
          this.errors = e.response.data.errors
          this.handleErrorFromServer(e.response.data.errors)
          this.snackbar = true
        }
      })
      if (data) {
        this.error = 'Добро пожаловать. Сейчас вы будете перенаправлены на главную страницу.'
        this.snackbar = true
      }

      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      await this.$store.dispatch('auth/fetchUser')

      var self = this
      setTimeout(function () { return self.$router.push({ name: 'main' }) }, 3000)
    },
    handleErrorFromServer (err) {
      let fields = ['email', 'password']
      for (let i = 0; i < 2; i++) {
        if (err.hasOwnProperty(fields[i])) {
          this.error = 'Неверный логин или пароль.'
        }
      }
    },
    validate () {
      this.$refs.form.validate()
    },
    reset () {
      this.$refs.form.reset()
    },
    resetValidation () {
      this.$refs.form.resetValidation()
    }
  }
}
</script>
