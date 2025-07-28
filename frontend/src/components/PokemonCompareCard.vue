<template>
  <q-card class="pokemon-compare-card q-pa-md q-mb-md" flat bordered :style="cardStyle">
    <!-- Hero Section -->
    <div class="image-section text-center">
      <q-img
        :src="pokemon.official_artwork_url ?? pokemon.sprite_url"
        :alt="pokemon.name ?? 'Unknown'"
        class="pokemon-image"
        fit="contain"
        spinner-color="grey-5"
        loading="lazy"
      />
      <div class="type-badges">
        <q-chip
          v-for="type in pokemon.types"
          :key="type"
          :label="type"
          :class="`type-${type}`"
          class="type-chip"
          text-color="white"
        />
      </div>
    </div>

    <!-- Basic Info -->
    <q-card-section class="main-info">
      <div class="pokemon-header">
        <h2 class="pokemon-name">{{ pokemon.name }}</h2>
        <div class="pokedex-number">#{{ pokemon.pokedex_number.toString().padStart(3, '0') }}</div>
      </div>
      <div class="pokemon-description">{{ pokemon.description }}</div>
    </q-card-section>

    <!-- Physical Stats -->
    <div class="stats-grid">
      <q-card flat bordered class="stat-card text-center">
        <q-icon name="straighten" size="28px" color="blue-5" />
        <div class="stat-value">{{ pokemon.height }}m</div>
        <div class="stat-label">Height</div>
      </q-card>

      <q-card flat bordered class="stat-card text-center">
        <q-icon name="fitness_center" size="28px" color="orange-5" />
        <div class="stat-value">{{ pokemon.weight }}kg</div>
        <div class="stat-label">Weight</div>
      </q-card>

      <q-card flat bordered class="stat-card text-center">
        <q-icon name="star" size="28px" color="yellow-6" />
        <div class="stat-value">{{ pokemon.base_experience }}</div>
        <div class="stat-label">Base XP</div>
      </q-card>
    </div>

    <!-- Battle Stats -->
    <q-card-section class="q-mt-md">
      <div class="section-title">
        <q-icon name="sports_mma" size="20px" class="q-mr-sm" color="red-5" />
        Battle Stats
      </div>

      <div class="stats-list">
        <div v-for="(value, stat) in pokemon.stats" :key="stat" class="stat-row">
          <div class="stat-name">{{ formatStatName(stat) }}</div>
          <div class="stat-bar-container">
            <q-linear-progress
              :value="value / 255"
              :color="getStatColor(value)"
              class="stat-bar"
              size="12px"
              rounded
            />
            <div class="stat-value-badge">{{ value }}</div>
          </div>
        </div>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  name: 'PokemonCompareCard',
  props: {
    pokemon: {
      type: Object,
      required: true,
    },
  },
  computed: {
    cardStyle() {
      const typeColors = {
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
      }

      const primary = this.pokemon?.types?.[0]
      const secondary = this.pokemon?.types?.[1]
      if (!primary) return {}

      const hex1 = typeColors[primary] || '#ccc'
      const hex2 = typeColors[secondary] || hex1

      return {
        background: `linear-gradient(135deg, ${hex1}15 0%, ${hex2}15 100%)`,
        border: `3px solid ${hex1}`,
        boxShadow: `0 8px 32px ${hex1}30`,
      }
    },
  },
  methods: {
    formatStatName(stat) {
      return stat.replace(/_/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase())
    },
    getStatColor(value) {
      if (value >= 100) return 'green'
      if (value >= 70) return 'light-green'
      if (value >= 50) return 'orange'
      return 'red'
    },
  },
}
</script>

<style scoped>
.pokemon-compare-card {
  border-radius: 20px;
  background: white;
}

.image-section {
  background: linear-gradient(45deg, #667eea, #764ba2);
  padding: 1.5rem;
  position: relative;
}

.pokemon-image {
  height: 150px;
  max-width: 250px;
  margin: 0 auto;
  filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.2));
}

.type-badges {
  position: absolute;
  top: 1rem;
  right: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.type-chip {
  font-weight: bold;
  text-transform: capitalize;
  border-radius: 16px;
}

.main-info {
  padding: 1.25rem 1rem;
}

.pokemon-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.pokemon-name {
  font-size: 1.6rem;
  font-weight: 700;
  text-transform: capitalize;
  color: #2c3e50;
  margin: 0;
}

.pokedex-number {
  background: #ecf0f1;
  padding: 0.5rem 1rem;
  border-radius: 1rem;
  font-weight: 600;
  font-size: 1rem;
  color: #7f8c8d;
}

.pokemon-description {
  margin-top: 0.75rem;
  color: #5a6c7d;
  font-size: 0.95rem;
}

.stats-grid {
  display: flex;
  justify-content: space-around;
  padding: 1rem 0.5rem 0;
}

.stat-card {
  width: 30%;
  border-radius: 15px;
}

.stat-value {
  font-weight: 700;
  color: #2c3e50;
  font-size: 1.3rem;
}

.stat-label {
  font-size: 0.85rem;
  color: #7f8c8d;
}

.section-title {
  font-weight: bold;
  font-size: 1.2rem;
  color: #2c3e50;
  display: flex;
  align-items: center;
  margin-bottom: 1rem;
}

.stats-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.stat-row {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.stat-name {
  min-width: 110px;
  font-weight: 500;
  color: #5a6c7d;
}

.stat-bar-container {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.stat-bar {
  flex: 1;
}

.stat-value-badge {
  min-width: 35px;
  text-align: center;
  background: #ecf0f1;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  color: #2c3e50;
}
</style>
