<template>
  <q-page class="q-pa-md">
    <div class="text-h3 q-mb-md text-center q-ma-md">
      <span
        class="capitalize text-white q-pa-sm q-px-xl rounded-borders"
        :style="{
          background: `linear-gradient(135deg, ${typeColors[type] || '#999'}cc, ${typeColors[type] || '#999'}99)`,
          boxShadow: `0 4px 8px ${typeColors[type] || '#999'}55`,
        }"
      >
        {{ type }}
      </span>
    </div>

    <div class="row q-col-gutter-md">
      <PokemonCard
        v-for="pokemon in pokemons"
        :key="pokemon.id"
        :pokemon="pokemon"
        @card-clicked="goToDetails"
        class="col-12 col-sm-6 col-md-4 col-lg-3"
      />
    </div>

    <!-- Loading spinner -->
    <div class="q-my-md flex flex-center" v-if="loading">
      <q-spinner color="primary" size="md" />
    </div>

    <!-- No more results message -->
    <div class="q-my-md text-center text-grey-6" v-if="!hasMore && pokemons.length > 0">
      ✨That's all {{ type.charAt(0).toUpperCase() + type.slice(1) }} Pokémon we have✨
    </div>

    <!-- Error message -->
    <div class="q-my-md text-center text-negative" v-if="error">
      <q-icon name="error" size="sm" class="q-mr-sm" />
      {{ error }}
      <q-btn flat color="primary" label="Retry" @click="retryLoad" class="q-ml-sm" />
    </div>

    <!-- Infinite scroll component -->
    <q-infinite-scroll
      @load="loadMore"
      :offset="200"
      :disable="!hasMore || loading || error"
      ref="infiniteScrollRef"
    >
      <template v-slot:loading>
        <div class="row justify-center q-my-md">
          <q-spinner-dots color="primary" size="40px" />
        </div>
      </template>
    </q-infinite-scroll>
  </q-page>
</template>

<script>
import { api } from 'boot/axios'
import PokemonCard from 'components/PokemonCard.vue'
import { useNavigationStore } from 'src/stores/navigation-store'

export default {
  name: 'PokemonTypePage',
  components: { PokemonCard },
  data() {
    return {
      type: this.$route.params.type,
      pokemons: [],
      lastId: 0,
      limit: 20,
      hasMore: true,
      loading: false,
      error: null,
      typeColors: {
        normal: '#A8A878',
        fire: '#F08030',
        water: '#6890F0',
        electric: '#F8D030',
        grass: '#78C850',
        ice: '#98D8D8',
        fighting: '#C03028',
        poison: '#A040A0',
        ground: '#E0C068',
        flying: '#A890F0',
        psychic: '#F85888',
        bug: '#A8B820',
        rock: '#B8A038',
        ghost: '#705898',
        dragon: '#7038F8',
        dark: '#705848',
        steel: '#B8B8D0',
        fairy: '#EE99AC',
      },
    }
  },
  async mounted() {
    // Load initial data
    await this.loadMore()
  },
  watch: {
    // Watch for route changes (if user navigates to different type)
    '$route.params.type': {
      handler(newType) {
        if (newType !== this.type) {
          this.resetAndLoad(newType)
        }
      },
      immediate: false,
    },
  },
  methods: {
    async loadMore(index, done) {
      // Prevent multiple simultaneous requests
      if (this.loading || !this.hasMore) {
        if (done) done()
        return
      }

      this.loading = true
      this.error = null

      try {
        const response = await api.get(`/pokemon/type/${this.type}`, {
          params: {
            limit: this.limit,
            last_id: this.lastId,
          },
        })

        const { data, next_last_id, has_more } = response.data

        // Add new pokemons to the list
        this.pokemons.push(...data)

        // Update pagination state
        this.lastId = next_last_id || this.lastId
        this.hasMore = has_more

        // If no more data, disable infinite scroll
        if (!has_more) {
          if (this.$refs.infiniteScrollRef) {
            this.$refs.infiniteScrollRef.stop()
          }
        }
      } catch (error) {
        console.error('Error loading Pokemon:', error)
        this.error = 'Failed to load Pokémon. Please try again.'
        this.hasMore = false
      } finally {
        this.loading = false
        // Call done to indicate loading is complete
        if (done) done()
      }
    },

    async retryLoad() {
      this.error = null
      this.hasMore = true
      if (this.$refs.infiniteScrollRef) {
        this.$refs.infiniteScrollRef.resume()
      }
      await this.loadMore()
    },

    async resetAndLoad(newType) {
      // Reset all state
      this.type = newType
      this.pokemons = []
      this.lastId = 0
      this.hasMore = true
      this.loading = false
      this.error = null

      // Reset infinite scroll
      if (this.$refs.infiniteScrollRef) {
        this.$refs.infiniteScrollRef.reset()
        this.$refs.infiniteScrollRef.resume()
      }

      // Load initial data
      await this.loadMore()
    },

    // manually trigger load more
    async refresh() {
      await this.resetAndLoad(this.type)
    },

    goToDetails(pokemon) {
      const navigateStore = useNavigationStore()

      navigateStore.enterTypeMode(this.type)
      console.log(this.type)

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

  // Cleanup
  beforeUnmount() {
    if (this.$refs.infiniteScrollRef) {
      this.$refs.infiniteScrollRef.stop()
    }
  },
}
</script>

<style scoped>
.capitalize {
  text-transform: capitalize;
}

/* Optional: Add some smooth transitions */
.q-infinite-scroll {
  min-height: 50px;
}

/* Loading states */
.loading-enter-active,
.loading-leave-active {
  transition: opacity 0.3s ease;
}

.loading-enter-from,
.loading-leave-to {
  opacity: 0;
}
</style>
