<template>
  <q-page class="q-pa-md pokemon-page q-ma-md">
    <div>
      <q-btn label="Back" color="primary" rounded-borders class="q-ma-sm" @click="handleBack" />
    </div>
    <div v-if="loading" class="text-center loading-container">
      <q-spinner size="60px" color="primary" />
      <div class="text-h6 q-mt-md text-grey-7">Loading Pokémon...</div>
    </div>

    <div v-else-if="error" class="text-negative text-center error-container">
      <q-icon name="error" size="48px" class="q-mb-md" />
      <div class="text-h6">{{ error }}</div>
    </div>

    <div v-else class="pokemon-container">
      <!-- Main Pokemon Card -->

      <test-card :pokemon="pokemon" />
    </div>
  </q-page>
</template>

<script>
import { useNavigationStore } from 'src/stores/navigation-store'
import TestCard from 'components/TestCard.vue'

export default {
  setup() {
    const navigateStore = useNavigationStore()
    return { navigateStore }
  },
  name: 'PokemonDetailsPage',

  components: {
    TestCard,
  },

  data() {
    return {
      pokemon: null,
      loading: true,
      error: null,
    }
  },
  async mounted() {
    const identifier = this.$route.params.identifier

    try {
      const res = await this.$api.get(`/pokemon/${identifier}`)
      this.pokemon = res.data
      console.log(res)
    } catch (err) {
      this.error = 'Could not load Pokémon details.'
      console.error(err)
    } finally {
      this.loading = false
    }
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

    handleBack() {
      if (this.navigateStore.mode === 'search') {
        this.$router.push({ name: 'pokedex', query: { q: this.navigateStore.searchQuery } })
      } else {
        const page = this.navigateStore.PageNo || 1
        this.$router.push({ name: 'pokedex', query: { page } })
      }
    },
  },
}
</script>

<style scoped>
.pokemon-page {
  max-width: 900px;
  /* margin: 0 auto; */
  margin: 3rem auto 1rem auto; /* top, horizontal, bottom */
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-height: 100vh;
  padding: 1rem;
}

.loading-container,
.error-container {
  padding: 4rem 1rem;
  margin: 2rem 0;
}

.pokemon-card {
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  background: white;
  margin: 0 0.5rem 1.5rem 0.5rem;
}

.image-section {
  position: relative;
  background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
  padding: 2rem;
  text-align: center;
}

.pokemon-image {
  height: 200px;
  max-width: 300px;
  margin: 0 auto;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
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
  font-weight: 600;
  text-transform: capitalize;
  border-radius: 20px;
}

/* Type-specific colors */
.type-bug {
  background-color: #a8b820 !important;
}
.type-fire {
  background-color: #f08030 !important;
}
.type-water {
  background-color: #6890f0 !important;
}
.type-grass {
  background-color: #78c850 !important;
}
.type-electric {
  background-color: #f8d030 !important;
}
.type-psychic {
  background-color: #f85888 !important;
}
.type-normal {
  background-color: #a8a878 !important;
}
.type-fighting {
  background-color: #c03028 !important;
}
.type-poison {
  background-color: #a040a0 !important;
}
.type-ground {
  background-color: #e0c068 !important;
}
.type-flying {
  background-color: #a890f0 !important;
}
.type-rock {
  background-color: #b8a038 !important;
}
.type-ghost {
  background-color: #705898 !important;
}
.type-dragon {
  background-color: #7038f8 !important;
}
.type-dark {
  background-color: #705848 !important;
}
.type-steel {
  background-color: #b8b8d0 !important;
}
.type-fairy {
  background-color: #ee99ac !important;
}
.type-ice {
  background-color: #98d8d8 !important;
}

.main-info {
  padding: 2rem;
}

.pokemon-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.pokemon-name {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0;
  text-transform: capitalize;
  color: #2c3e50;
}

.pokedex-number {
  font-size: 1.5rem;
  font-weight: 600;
  color: #7f8c8d;
  background: #ecf0f1;
  padding: 0.5rem 1rem;
  border-radius: 20px;
}

.pokemon-description {
  font-size: 1.1rem;
  line-height: 1.6;
  color: #5a6c7d;
  margin: 0;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1rem;
  margin: 0 0.5rem;
}

.stat-card {
  border-radius: 15px;
  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease;
  background: white;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  margin-bottom: 0.5rem;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.9rem;
  color: #7f8c8d;
  font-weight: 500;
}

.battle-stats-card {
  border-radius: 15px;
  background: white;
  /* margin: 0 0.5rem; */
}

.section-title {
  display: flex;
  align-items: center;
  font-size: 1.3rem;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 1.5rem;
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
  min-width: 120px;
  font-weight: 500;
  color: #5a6c7d;
  text-transform: capitalize;
}

.stat-bar-container {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.stat-bar {
  flex: 1;
  border-radius: 6px;
}

.stat-value-badge {
  background: #ecf0f1;
  color: #2c3e50;
  padding: 0.25rem 0.5rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  min-width: 35px;
  text-align: center;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
  .pokemon-page {
    padding: 0.5rem;
  }

  .pokemon-card {
    margin: 0 0 1.5rem 0;
  }

  .stats-grid,
  .battle-stats-card {
    margin: 0;
  }

  .image-section {
    padding: 1.5rem;
  }

  .pokemon-image {
    height: 160px;
  }

  .main-info {
    padding: 1.5rem;
  }

  .pokemon-header {
    flex-direction: column;
    text-align: center;
    gap: 0.5rem;
  }

  .pokemon-name {
    font-size: 2rem;
  }

  .pokedex-number {
    font-size: 1.2rem;
  }

  .stats-grid {
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
  }

  .stat-value {
    font-size: 1.2rem;
  }

  .stat-label {
    font-size: 0.8rem;
  }
}

@media (max-width: 480px) {
  .pokemon-page {
    padding: 0.25rem;
  }

  .type-badges {
    position: static;
    margin-top: 1rem;
    display: flex;
    flex-direction: row;
    justify-content: center;
    gap: 0.5rem;
  }

  .pokemon-name {
    font-size: 1.8rem;
  }

  .stats-grid {
    grid-template-columns: 1fr;
    gap: 0.5rem;
  }

  .stat-row {
    flex-direction: column;
    text-align: center;
    gap: 0.5rem;
  }

  .stat-name {
    min-width: auto;
    font-size: 0.9rem;
  }

  .stat-bar-container {
    width: 100%;
    flex-direction: column;
    gap: 0.5rem;
  }

  .stat-bar {
    width: 100%;
  }
}

/* Tablet responsiveness */
@media (min-width: 769px) and (max-width: 1024px) {
  .pokemon-page {
    padding: 1.5rem;
  }

  .stats-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
</style>
