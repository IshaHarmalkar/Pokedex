<template>
  <q-page class="pokemon-page">
    <div class="container q-pa-md">
      <div class="text-center q-mb-lg">
        <!-- <h1 class="text-h3 text-weight-bold q-mb-md text-secondary">Random Pokémon Generator</h1> -->
        <q-btn
          @click="fetchRandomPokemon"
          :loading="loading"
          size="lg"
          class="generate-btn q-mt-md"
          icon="casino"
          label="Generate Random Pokémon"
        />
      </div>

      <div v-if="pokemon && !loading" class="pokemon-card-container">
        <q-card class="pokemon-card" :style="cardStyle">
          <!-- Header with Pokemon name and number -->
          <q-card-section class="pokemon-header text-center">
            <div class="pokemon-number">#{{ String(pokemon.pokedex_number).padStart(3, '0') }}</div>
            <h2 class="pokemon-name">{{ capitalize(pokemon.name) }}</h2>

            <!-- Types -->
            <div class="pokemon-types q-mt-sm">
              <q-chip
                v-for="type in pokemon.types"
                :key="type"
                :style="getTypeStyle(type)"
                class="type-chip text-weight-bold"
                text-color="white"
              >
                {{ capitalize(type) }}
              </q-chip>
            </div>
          </q-card-section>

          <!-- Pokemon Image -->
          <q-card-section class="pokemon-image-section text-center">
            <img
              :src="pokemon.official_artwork_url"
              :alt="pokemon.name"
              class="pokemon-image"
              @error="useSprite"
            />
          </q-card-section>

          <!-- Pokemon Info -->
          <q-card-section class="pokemon-info">
            <p class="pokemon-description text-center q-mb-lg">
              {{ pokemon.description }}
            </p>

            <!-- Basic Info Grid -->
            <div class="info-grid q-mb-lg">
              <div class="info-item">
                <q-icon name="straighten" size="sm" class="q-mr-xs" />
                <span class="info-label">Height:</span>
                <span class="info-value">{{ pokemon.height }}m</span>
              </div>
              <div class="info-item">
                <q-icon name="fitness_center" size="sm" class="q-mr-xs" />
                <span class="info-label">Weight:</span>
                <span class="info-value">{{ pokemon.weight }}kg</span>
              </div>
              <div class="info-item">
                <q-icon name="stars" size="sm" class="q-mr-xs" />
                <span class="info-label">Base XP:</span>
                <span class="info-value">{{ pokemon.base_experience }}</span>
              </div>
            </div>

            <!-- Stats -->
            <div class="stats-section">
              <h3 class="stats-title text-center q-mb-md">Base Stats</h3>
              <div class="stats-grid">
                <div v-for="(value, stat) in pokemon.stats" :key="stat" class="stat-item">
                  <div class="stat-name">{{ formatStatName(stat) }}</div>
                  <div class="stat-bar-container">
                    <div
                      class="stat-bar"
                      :style="{
                        width: `${(value / 255) * 100}%`,
                        backgroundColor: getStatColor(value),
                      }"
                    ></div>
                  </div>
                  <div class="stat-value">{{ value }}</div>
                </div>
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center q-mt-xl">
        <q-spinner-pokeball size="80px" color="primary" />
        <p class="text-h6 q-mt-md text-white">Catching a random Pokémon...</p>
      </div>

      <!-- Error State -->
      <q-banner v-if="error" class="bg-negative text-white q-mt-md" rounded>
        <template v-slot:avatar>
          <q-icon name="error" />
        </template>
        {{ error }}
        <template v-slot:action>
          <q-btn flat label="Try Again" @click="fetchRandomPokemon" />
        </template>
      </q-banner>
    </div>
  </q-page>
</template>

<script>
export default {
  name: 'PokemonRandomPage',

  data() {
    return {
      pokemon: null,
      loading: false,
      error: null,

      // Pokemon type colors (official colors)
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

  computed: {
    cardStyle() {
      if (!this.pokemon?.types?.length) return {}

      const primaryType = this.pokemon.types[0]
      const secondaryType = this.pokemon.types[1]

      if (secondaryType) {
        return {
          background: `linear-gradient(135deg, ${this.typeColors[primaryType]}15 0%, ${this.typeColors[secondaryType]}15 100%)`,
          border: `3px solid ${this.typeColors[primaryType]}`,
          boxShadow: `0 8px 32px ${this.typeColors[primaryType]}30`,
        }
      } else {
        return {
          background: `linear-gradient(135deg, ${this.typeColors[primaryType]}15 0%, ${this.typeColors[primaryType]}25 100%)`,
          border: `3px solid ${this.typeColors[primaryType]}`,
          boxShadow: `0 8px 32px ${this.typeColors[primaryType]}30`,
        }
      }
    },
  },

  async mounted() {
    await this.fetchRandomPokemon()
  },

  methods: {
    async fetchRandomPokemon() {
      this.loading = true
      this.error = null

      try {
        const res = await this.$api.get('/pokemon/random')
        this.pokemon = res.data
      } catch (err) {
        this.error = 'Failed to fetch random Pokémon. Please try again.'
        console.error('Error fetching Pokemon:', err)
        this.pokemon = null
      } finally {
        this.loading = false
      }
    },

    capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1)
    },

    getTypeStyle(type) {
      return {
        backgroundColor: this.typeColors[type] || '#68A090',
        boxShadow: `0 2px 8px ${this.typeColors[type]}40`,
      }
    },

    formatStatName(stat) {
      const statNames = {
        hp: 'HP',
        attack: 'Attack',
        defense: 'Defense',
        special_attack: 'Sp. Attack',
        special_defense: 'Sp. Defense',
        speed: 'Speed',
      }
      return statNames[stat] || this.capitalize(stat)
    },

    getStatColor(value) {
      if (value >= 100) return '#4CAF50' // Green
      if (value >= 70) return '#FF9800' // Orange
      if (value >= 40) return '#FFC107' // Amber
      return '#F44336' // Red
    },

    useSprite(event) {
      if (this.pokemon?.sprite_url) {
        event.target.src = this.pokemon.sprite_url
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.pokemon-page {
  min-height: 100vh;
  background: #f5f5f5;
  color: #333;
}

.container {
  max-width: 700px;
  margin: 0 auto;
  padding: 1rem;
}

.generate-btn {
  font-size: 1rem;
  padding: 10px 24px;
  border: #3f51b5 2px solid;
  box-shadow: none;
  text-transform: none;
  color: $secondary;

  &:hover {
    background-color: #303f9f;
  }
}

.pokemon-card {
  width: 100%;
  border: 1px solid #ddd;
  border-radius: 12px;
  background-color: #fff;
}

.pokemon-header {
  padding: 1rem;
  border-bottom: 1px solid #eee;
  text-align: center;
}

.pokemon-number {
  font-size: 0.9rem;
  color: #777;
}

.pokemon-name {
  font-size: 1.8rem;
  font-weight: 500;
  margin: 0.2rem 0;
}

.pokemon-types {
  display: flex;
  justify-content: center;
  gap: 0.4rem;
  flex-wrap: wrap;
  margin-top: 0.5rem;
}

.type-chip {
  font-size: 0.75rem;
  padding: 4px 10px;
  border-radius: 12px;
  text-transform: uppercase;
  background-color: #ccc;
  color: white;
}

.pokemon-image-section {
  padding: 1.5rem;
  text-align: center;
}

.pokemon-image {
  max-width: 200px;
  height: auto;
  filter: none;
}

.pokemon-info {
  padding: 1.5rem;
}

.pokemon-description {
  font-size: 1rem;
  color: #666;
  text-align: center;
  margin-bottom: 1rem;
  font-style: normal;
}

.info-grid {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.info-item {
  display: flex;
  align-items: center;
  font-size: 0.9rem;
  color: #444;
}

.info-label {
  font-weight: 500;
  margin-right: 0.4rem;
}

.info-value {
  font-weight: 600;
  color: #555;
}

.stats-section {
  margin-top: 1rem;
}

.stats-title {
  font-size: 1.2rem;
  margin-bottom: 1rem;
  text-align: center;
}

.stats-grid {
  display: grid;
  gap: 0.75rem;
}

.stat-item {
  display: grid;
  grid-template-columns: 80px 1fr 40px;
  align-items: center;
}

.stat-name {
  font-size: 0.85rem;
  color: #444;
}

.stat-bar-container {
  height: 6px;
  background: #eee;
  border-radius: 4px;
}

.stat-bar {
  height: 100%;
  border-radius: 4px;
  transition: width 0.4s ease-in-out;
}

.stat-value {
  font-size: 0.85rem;
  text-align: right;
  color: #444;
}
</style>
