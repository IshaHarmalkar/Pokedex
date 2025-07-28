<template>
  <q-page class="q-pa-md compare-page q-ma-md">
    <div class="q-md-md">
      <q-btn label="Back" color="primary" rounded @click="$router.back()" class="q-mx-md" />

      <div class="q-ma-md">
        <pokemon-compare-input
          :modelFirst="firstName"
          :modelSecond="secondName"
          @compare="comparePokemon"
        />
      </div>

      <div v-if="loading" class="text-center">
        <q-spinner size="50px" color="primary" />
        <div class="q-mt-sm text-subtitle2">Loading COmaprison...</div>
      </div>

      <div v-else-if="error" class="text-negative text-center">
        <q-icon name="error_outline" size="48px" />
        <div class="text-subtitle1">{{ error }}</div>
      </div>

      <div v-else class="row q-col-gutter-md">
        <div class="col-12 col-md-6">
          <!-- Pokemon 1 -->
          <PokemonCompareCard :pokemon="comparison.first" />
        </div>

        <div class="col-12 col-md-6">
          <!-- Pokemon 2 -->
          <PokemonCompareCard :pokemon="comparison.second" />
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import PokemonCompareCard from 'src/components/PokemonCompareCard.vue'
import PokemonCompareInput from 'src/components/PokemonCompareInput.vue'

export default {
  name: 'ComparePage',
  components: {
    PokemonCompareCard,
    PokemonCompareInput,
  },

  data() {
    return {
      comparison: null,
      loading: true,
      error: null,
      firstName: this.$route.query.first || '',
      secondName: this.$route.query.second || '',
    }
  },

  watch: {
    '$route.query': {
      handler() {
        this.loadComparison()
      },
      immediate: true,
    },
  },

  methods: {
    async loadComparison() {
      const { first, second } = this.$route.query
      if (!first || !second) return

      this.loading = true
      this.error = null

      try {
        const res = await this.$api.get('/pokemon/compare', {
          params: { first, second },
        })

        this.comparison = res.data
      } catch (err) {
        this.error = 'Failed to load comparison data.'
        console.error(err)

        this.comparison = null
      } finally {
        this.loading = false
      }
    },

    comparePokemon({ first, second }) {
      this.firstName = first
      this.secondName = second
      this.$router.push({
        name: 'compare',
        query: { first, second },
      })
    },
  },

  async mounted() {
    const { first, second } = this.$route.query

    if (!first || !second) {
      this.error = 'Missing Pokemon to compare.'
      this.loading = false
      return
    }

    try {
      const res = await this.$api.get(`/pokemon/compare`, {
        params: { first, second },
      })
      this.comparison = res.data
    } catch (err) {
      this.error = 'Failed to load comparison data.'
      console.error(err)
    } finally {
      this.loading = false
    }
  },
}
</script>

<style lang="scss" scoped>
.compare-page {
  max-width: 1100px;
  margin: 2rem auto;
}
</style>
