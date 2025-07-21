<template>
  <q-page class="q-pa-md">
    <q-card class="q-pa-md rounded-borders" flat bordered>
      <div v-if="pokemon" class="row q-col-gutter-md">
        <!-- Reused Card Component -->
        <div class="col-12 col-md-4">
          <PokemonCard :pokemon="pokemon" />
        </div>

        <!-- Detailed Info Section -->
        <div class="col-12 col-md-8">
          <div class="text-h5 text-primary q-mb-sm">Details</div>

          <!-- Description -->
          <div class="q-mb-md">
            <div class="text-subtitle1 text-bold">Description</div>
            <div class="text-body1">{{ pokemon.description }}</div>
          </div>

          <!-- Stats -->
          <div class="q-mb-md">
            <div class="text-subtitle1 text-bold">Stats</div>
            <q-linear-progress
              v-for="(value, stat) in pokemon.stats"
              :key="stat"
              :value="value / 100"
              color="accent"
              track-color="grey-3"
              class="q-mt-sm"
            >
              <div class="text-caption">{{ stat }}: {{ value }}</div>
            </q-linear-progress>
          </div>

          <!-- Physical Attributes -->
          <div class="row q-gutter-md">
            <div>
              <q-icon name="height" class="q-mr-xs" />
              <span><strong>Height:</strong> {{ pokemon.height }} m</span>
            </div>
            <div>
              <q-icon name="fitness_center" class="q-mr-xs" />
              <span><strong>Weight:</strong> {{ pokemon.weight }} kg</span>
            </div>
          </div>
        </div>
      </div>

      <div v-else-if="loading" class="text-center q-mt-md">
        <q-spinner color="primary" size="40px" />
      </div>

      <div v-else-if="error" class="text-negative text-center q-mt-md">
        {{ error }}
      </div>
    </q-card>
  </q-page>
</template>

<script>
import PokemonCard from 'src/components/PokemonCard.vue'

export default {
  name: 'PokemonDetails',
  components: {
    PokemonCard,
  },
  props: {
    identifier: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      pokemon: null,
      loading: true,
      error: null,
    }
  },

  async mounted() {
    try {
      console.log(this.identifier)
      const res = await this.$api.get(`/pokemon/${this.identifier}`)
      this.pokemon = res.data
    } catch (err) {
      this.error = 'Failed to load Pokémon'
      console.error(err)
    } finally {
      this.loading = false
    }
  },
}
</script>

<style scoped>
.rounded-borders {
  border-radius: 16px;
}
</style>
