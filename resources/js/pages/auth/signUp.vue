<template>
  <v-container fluid>
    <v-container :style="{ 'max-width' : '600px' }">
      <v-stepper v-model="currentStep" vertical class="elevation-0">
        <v-stepper-step :complete="currentStep > 1" step="1" color="grey darken-3">
          Введите инвайт-код
        </v-stepper-step>

        <v-stepper-content step="1">
          <v-form
            ref="form"
            v-model="valid"
            :lazy-validation="lazy"
          >
            <v-text-field
              v-model="invite_code"
              :counter="255"
              label="Инвайт-код"
              required
            />
          </v-form>

          <v-btn
            color="grey darken-3"
            class="white--text"
            :disabled="!invite_code"
            @click="checkInvite()"
          >
            Продолжить
          </v-btn>
          <v-btn text @click="currentStep = 1; invite_code = ''">
            Отмена
          </v-btn>
        </v-stepper-content>

        <v-stepper-step :complete="currentStep > 2" step="2" color="grey darken-3">
          Заполните данные
        </v-stepper-step>

        <v-stepper-content step="2">
          <v-form
            ref="form"
            v-model="valid"
            :lazy-validation="lazy"
          >
            <v-text-field
              v-model="name"
              :counter="16"
              :rules="nameRules"
              label="Логин"
              required
            />

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
          </v-form>
          <div class="mt-5">
            <v-btn
              color="grey darken-3"
              class="white--text"
              :disabled="!valid"
              @click="validate; signUp()"
            >
              Регистрация
            </v-btn>
            <v-btn text @click="reset">
              Отмена
            </v-btn>
          </div>
        </v-stepper-content>
      </v-stepper>
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
  data: () => ({
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
    password: '',
    passRules: [
      v => !!v || 'Пароль обязателен',
      v => (v && v.length >= 6) || 'Пароль должен быть больше 6 символов.',
      v => (v && v.length < 32) || 'Пароль должен быть не больше 32 символов.'
    ],
    invite_code: '',
    lazy: false,
    currentStep: 1,
    snackbar: false,
    error: ''
  }),

  methods: {
    async checkInvite () {
      const { data } = await this.$http.post('/api/checkInvite', { invite_code: this.invite_code })
      if (data.hasOwnProperty('Error')) {
        this.error = data.Error
        this.snackbar = true
      } else if (data.hasOwnProperty('Success')) {
        this.currentStep = 2
      }
    },
    async signUp () {
      let userData = { name: this.name, email: this.email, password: this.password, invite_code: this.invite_code }
      const { data } = await this.$http.post('/api/register', userData).catch(e => {
        if (e.response.status === 422) { this.errors = e.response.data.errors; this.handleErrorFromServer(e.response.data.errors); this.snackbar = true }
      })
      if (data) {
        const { data: { token } } = await this.$http.post('/api/login', { email: this.email, password: this.password })
        this.$store.dispatch('auth/saveToken', { token })
        await this.$store.dispatch('auth/updateUser', { user: data })
        this.$router.push({ name: 'main' })
      }
    },
    handleErrorFromServer (err) {
      let fields = ['name', 'email', 'password']
      let exceptions = ['Такой логин уже существует.', 'Такой e-mail невалиден или уже существует.', 'Пароль невалиден.']
      for (let i = 0; i < 3; i++) {
        if (err.hasOwnProperty(fields[i])) {
          this.error = exceptions[i]
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
