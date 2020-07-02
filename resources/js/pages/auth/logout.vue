<template>
  <div />
</template>

<script>
export default {
  mounted () {
    this.logout()
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push({ name: 'login' })
    }
  }
}
</script>
