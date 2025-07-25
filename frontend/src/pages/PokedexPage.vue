<template>
  <q-page class="q-pa-md q-mt-md">
    <!-- Pokemon Grid -->
    <div class="row q-col-gutter-md">
      <template v-if="loading">
        <div v-for="n in 20" :key="'skeleton-' + n" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
          <q-skeleton height="220px" square class="q-mb-sm" />
        </div>
      </template>

      <template v-else>
        <div
          v-for="pokemon in pokemons"
          :key="pokemon.id"
          class="col-xs-12 col-sm-6 col-md-4 col-lg-3"
        >
          <PokemonCard :pokemon="pokemon" @card-clicked="goToDetails" />
        </div>
      </template>
    </div>

    <div v-if="error" class="text-negative text-center q-mt-md">
      {{ error }}
    </div>

    <div class="row justify-center q-mt-lg">
      <q-btn
        label="Previous"
        color="primary"
        :disable="!prevPageUrl"
        @click="goToUrl(prevPageUrl)"
        class="q-mr-sm"
      />

      <q-btn label="Next" color="primary" :disable="!nextPageUrl" @click="goToUrl(nextPageUrl)" />
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
      cachedNextPage: null,
      loading: false,
      error: null,
      currentPage: 1,
      lastPage: null,
      nextPageUrl: null,
      prevPageUrl: null,
      paginationMeta: {},
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
    async fetchPokemons(url = 'pokemon') {
      this.loading = true
      this.error = null

      try {
        const data = await this.getPokemonPage(url)
        this.applyPageData(data)
        this.prefetchNextPage(data.next_page_url)
      } catch (err) {
        this.error = 'Failed to load Pokemon'
        console.log(err)
      } finally {
        this.loading = false
      }
    },

    async getPokemonPage(url) {
      const res = await this.$api.get(url)
      return res.data
    },

    applyPageData(data) {
      this.pokemons = data.data
      this.nextPageUrl = data.next_page_url
      this.prevPageUrl = data.prev_page_url
      this.paginationMeta = {
        total: data.total,
        current: data.current_page,
        perPage: data.per_page,
        lastPage: data.last_page,
      }

      this.preloadImagesForCurrentPage()
    },

    async prefetchNextPage(url) {
      if (!url) {
        this.cachedNextPage = null
        return
      }

      try {
        const res = await this.$api.get(url)
        this.cachedNextPage = res.data

        // Preload images for next page
        for (const pokemon of res.data.data || []) {
          const img = new Image()
          img.src = pokemon.official_artwork_url || pokemon.sprite_url
        }
      } catch {
        this.cachedNextPage = null
      }
    },

    async goToUrl(url) {
      if (url === this.nextPageUrl && this.cachedNextPage) {
        this.applyPageData(this.cachedNextPage)
        this.prefetchNextPage(this.cachedNextPage.next_page_url)
        this.cachedNextPage = null
      } else {
        await this.fetchPokemons(url)
      }
    },

    preloadImagesForCurrentPage() {
      for (const pokemon of this.pokemons) {
        const img = new Image()
        img.src = pokemon.official_artwork_url || pokemon.sprite_url
      }
    },

    goToDetails(pokemon) {
      const identifier = pokemon.name?.toLowerCase() || pokemon.pokedex_number || pokemon.id
      const imageUrl = pokemon.official_artwork_url || pokemon.sprite_url
      if (imageUrl) {
        const preloadImg = new Image()
        preloadImg.src = imageUrl
      }
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
