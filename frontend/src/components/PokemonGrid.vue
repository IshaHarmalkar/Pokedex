<template>
  <div class="pokemon-grid">
    <!-- Loading State -->
    <div v-if="loading" class="row q-gutter-md">
      <div v-for="n in 20" :key="n" class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12">
        <q-card class="pokemon-card-skeleton">
          <q-skeleton height="200px" square />
          <q-card-section>
            <q-skeleton type="text" class="text-subtitle1" />
            <q-skeleton type="text" width="60%" />
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Pokemon Grid -->
    <div v-else class="row q-gutter-md">
      <div
        v-for="pokemon in pokemonList"
        :key="pokemon.id"
        class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12"
      >
        <pokemon-card :pokemon="pokemon" @click="$emit('select-pokemon', pokemon)" />
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && pokemonList.length === 0" class="text-center q-pa-xl">
      <q-icon name="catching_pokemon" size="4rem" color="grey-5" />
      <div class="text-h6 text-grey-6 q-mt-md">No Pokémon found</div>
      <div class="text-grey-5">Try adjusting your search criteria</div>
    </div>
  </div>
</template>

<script>
import PokemonCard from './PokemonCard.vue'

export default {
  name: 'PokemonGrid',
  components: {
    PokemonCard,
  },
  props: {
    pokemonList: {
      type: Array,
      default: () => [],
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['select-pokemon'],
}
</script>

<style lang="scss" scoped>
.pokemon-grid {
  min-height: 400px;
}

.pokemon-card-skeleton {
  min-height: 280px;
}
</style>
