<template>
  <q-page class="flex flex-center">
    <router-link to="/pokemon">
      <q-btn label="Home" />
    </router-link>
    <q-btn label="Test API" @click="testApi" :loading="loading" />
    <div v-if="testData">
      <pre>{{ testData }}</pre>
    </div>

    <div v-if="error" class="test-negative">Error: {{ error }}</div>
  </q-page>
</template>

<script>
// import { api } from 'boot/axios'

export default {
  name: 'IndexPage',
  data() {
    return {
      testData: null,
      loading: false,
      error: null,
    }
  },

  methods: {
    async testApi() {
      this.loading = true
      try {
        const response = await this.$api('/pokemon')
        this.testData = response.data
        this.error = null
      } catch (err) {
        this.error = err.message
        console.error('Api Error: ', err)
      }

      this.loading = false
    },
  },
}
</script>
