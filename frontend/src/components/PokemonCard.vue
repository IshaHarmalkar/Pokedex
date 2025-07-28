<template>
  <div @click="$emit('card-clicked', pokemon)" style="cursor: pointer" class="card-container">
    <q-card
      class="q-ma-sm q-pa-sm rounded-borders cursor-pointer"
      flat
      square
      :style="cardBorderStyle"
      @click="$emit('card-clicked', pokemon)"
    >
      <q-img
        :src="pokemon.official_artwork_url ?? pokemon.sprite_url"
        :alt="pokemon.name ?? 'Unkown'"
        spinner-color="grey-5"
        style="height: 150px"
        fit="contain"
        loading="lazy"
      />

      <q-card-section class="text-center">
        <div class="text-h6 capitalize">
          {{ pokemon.name }}
        </div>
        <div class="row justify-center q-gutter-xs">
          <q-chip
            v-for="type in pokemon.types"
            :key="type"
            :label="type"
            color="typeColorMap[type.toLowerCase()] || 'grey-5'"
            dense
            :class="`chip-border-${type.toLowerCase()}`"
          />
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
export default {
  name: 'PokemonCard',
  props: {
    pokemon: {
      type: Object,
      required: true,
    },
  },

  computed: {
    typeColorMap() {
      return {
        normal: '#A8A77A',
        fire: '#EE8130',
        water: '#6390F0',
        electric: '#F7D02C',
        grass: '#7AC74C',
        ice: '#96D9D6',
        fighting: '#C22E28',
        poison: '#A33EA1',
        ground: '#E2BF65',
        flying: '#A98FF3',
        psychic: '#F95587',
        bug: '#A6B91A',
        rock: '#B6A136',
        ghost: '#735797',
        dragon: '#6F35FC',
        dark: '#705746',
        steel: '#B7B7CE',
        fairy: '#D685AD',
      }
    },

    cardBorderStyle() {
      const types = this.pokemon.types || []
      const colors = types.map((type) => this.typeColorMap[type.toLowerCase()] || '#ccc')

      if (colors.length === 1) {
        return {
          border: `3px solid${colors[0]}`,
          'border-radius': '8px',
        }
      }

      if (colors.length >= 2) {
        return {
          border: '3px solid',
          'border-image': `linear-gradient(45deg, ${colors.join(', ')}) 1`,
          'border-radius': '8px',
        }
      }

      return {} // fallback
    },
  },
}
</script>

<style lang="scss" scoped>
// .rounded-borders {
//   border-radius: 8px;
// }

.q.card {
}

.q-card:hover {
  transform: scale(1.02);
  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  cursor: pointer;
}

.q-chip {
  background-color: white;
  padding: 10px;
  border-radius: 4px;
}

.card-container {
}
</style>
