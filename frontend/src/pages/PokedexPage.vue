<template>
  <q-page class="q-pa-md q-mt-md">
    <!-- Search Bar -->
    <SearchBar
      :initial-query="navigateStore.searchQuery"
      @search="handleSearch"
      @search-cleared="onSearchCleared"
      class="q-mb-md"
    />

    <!-- Pokemon Grid -->
    <div class="row q-col-gutter-md">
      <template v-if="loading">
        <div v-for="n in 20" :key="'skeleton-' + n" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
          <q-skeleton height="220px" square class="q-mb-sm" />
        </div>
      </template>

      <template v-else>
        <div
          v-for="pokemon in isSearching ? searchResults : pokemons"
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

    <div v-if="searchError" class="text-negative text-center q-mt-md">
      {{ searchError }}
    </div>

    <div v-if="!isSearching" class="row justify-center q-mt-lg">
      <q-btn
        label="Previous"
        color="primary"
        :disable="!prevPageUrl"
        @click="goToPrevious"
        class="q-mr-sm"
      />

      <q-btn label="Next" color="primary" :disable="!nextPageUrl" @click="goToNext" />
    </div>
  </q-page>
</template>

<script>
import SearchBar from 'src/components/SearchBar.vue'
import { useNavigationStore } from 'src/stores/navigation-store'
import { mapStores } from 'pinia'
import PokemonCard from 'src/components/PokemonCard.vue'

export default {
  name: 'PokedexPage',
  components: {
    PokemonCard,
    SearchBar,
  },

  data() {
    return {
      pokemons: [],
      cachedNextPage: null,
      loading: false,
      error: null,
      nextPageUrl: null,
      prevPageUrl: null,
      searchResults: [],
      searchError: null,
    }
  },

  computed: {
    ...mapStores(useNavigationStore),

    isSearching() {
      return this.navigateStore.mode === 'search'
    },
  },

  mounted() {
    const page = this.navigateStore.PageNo

    if (
      this.navigateStore.mode === 'pokedex' &&
      this.cachedNextPage &&
      this.cachedNextPage.current_page === page
    ) {
      this.applyPageData(this.cachedNextPage)
    } else {
      this.fetchPokemons(`pokemon?page=${page}`)
    }

    const q = this.$route.query.q
    if (q && this.navigateStore.mode === 'search') {
      this.handleSearch(q)
    }
  },
  methods: {
    async fetchPokemons(url) {
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

      // save current page in store
      this.navigateStore.setPage(data.current_page)

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

    async goToNext() {
      if (!this.nextPageUrl) return

      this.navigateStore.nextPage()

      if (this.cachedNextPage) {
        this.applyPageData(this.cachedNextPage)
        this.prefetchNextPage(this.cachedNextPage.next_page_url)
        this.cachedNextPage = null
      } else {
        this.loading = true

        try {
          await this.fetchPokemons(this.nextPageUrl)
        } finally {
          this.loading = false
        }
      }
    },

    async goToPrevious() {
      if (!this.prevPageUrl) return

      this.navigateStore.prevPage()
      await this.fetchPokemons(this.prevPageUrl)
    },

    preloadImagesForCurrentPage() {
      for (const pokemon of this.pokemons) {
        const img = new Image()
        img.src = pokemon.official_artwork_url || pokemon.sprite_url
      }
    },

    //search functions

    async handleSearch(query) {
      this.loading = true
      this.searchError = null
      this.navigateStore.enterSearchMode(query)

      try {
        const res = await this.$api.get(`/pokemon/search?q=${encodeURIComponent(query)}`)
        this.searchResults = res.data
      } catch (err) {
        if (err.response && err.response.status === 404) {
          this.searchError = 'No Pokemon found'
        } else {
          this.searchError = 'Search Failed'
        }
        this.searchResults = []
      } finally {
        this.loading = false
      }
    },
    onSearchCleared() {
      this.searchResults = []
      this.searchError = null
      this.navigateStore.exitSearchMode()
      this.fetchPokemons(`pokemon?page=${this.navigateStore.PageNo}`)
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
