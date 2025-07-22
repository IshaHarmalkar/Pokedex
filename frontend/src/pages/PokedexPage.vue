<template>
  <q-page class="q-pa-md q-mt-md">
    <div class="row q-col-gutter-md">
      <div
        v-for="pokemon in pokemons"
        :key="pokemon.id"
        class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
      >
        <PokemonCard :pokemon="pokemon" @card-clicked="goToDetails" />
      </div>
    </div>

    <div v-if="loading" class="text-center q-mt-md">
      <q-spinner color="primary" size="40px" />
    </div>

    <div v-if="error" class="text-negative text-center q-mt-md">
      {{ error }}
    </div>

    <div class="row justify-center q-mt-lg">
      <q-btn
        label="Previous"
        color="primary"
        :disable="!prevPageUrl"
        @click="fetchPokemons(currentPage - 1)"
        class="q-mr-sm"
      />

      <q-btn
        label="Next"
        color="primary"
        :disable="!nextPageUrl"
        @click="fetchPokemons(currentPage + 1)"
      />
    </div>
  </q-page>
</template>

<script>
import PokemonCard from 'src/components/PokemonCard.vue'

export default {
  name: 'PokedexPage',
  components: {
    PokemonCard,
  },

  data() {
    return {
      pokemons: [],
      loading: true,
      error: null,
      currentPage: 1,
      lastPage: null,
      nextPageUrl: null,
      prevPageUrl: null,
    }
  },

  computed: {
    getGridCols() {
      if (this.$q.screen.lt.sm) return 1
      if (this.$q.screen.lt.md) return 2
      if (this.$q.screen.lt.lg) return 3
      return 4
    },
  },

  mounted() {
    this.fetchPokemons()
  },
  methods: {
    async fetchPokemons(page = 1) {
      this.loading = true
      this.error = null
      try {
        const res = await this.$api.get(`/pokemon?page=${page}`)
        this.pokemons = res.data.data
        this.currentPage = res.data.current_page
        this.lastPage = res.data.last_page
        this.nextPageUrl = res.data.next_page_url
        this.prevPageUrl = res.data.prev_page_url
      } catch (err) {
        this.error = 'Failed to load Pokemon'
        console.log(err)
      } finally {
        this.loading = false
      }
    },
    goToDetails(pokemon) {
      const identifier = pokemon.name?.toLowerCase() || pokemon.pokedex_number || pokemon.id
      this.$router.push({
        name: 'pokemon-details',
        params: {
          identifier: identifier,
        },
      })
    },
  },
}
</script>

<style lang="scss" scoped></style>
