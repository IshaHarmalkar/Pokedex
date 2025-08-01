<template>
  <div>
    <q-card
      @click="$emit('click')"
      class="cursor-pointer text-white q-pa-md q-ma-sm flex flex-center glow-effect"
      :style="{
        background: gradientMap[type] || 'linear-gradient(135deg, #999, #666)',
        width: '340px',
        height: '340px',
        boxShadow: `0 0 15px 5px ${iconColorMap[type] || '#fff'}`,
      }"
    >
      <img
        :src="pokemonImageMap[type]"
        alt="Type Pokémon"
        class="q-mb-md"
        style="width: 220px; height: 220px; filter: drop-shadow(3px 3px 6px rgba(0, 0, 0, 0.4))"
      />
      <q-card-section>
        <div class="text-h4 text-capitalize">{{ type }}</div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
export default {
  name: 'TypeCard',
  props: {
    type: String,
  },

  data() {
    return {
      gradientMap: {
        fire: 'linear-gradient(120deg, #F08030, #FF6B35)',
        water: 'linear-gradient(135deg, #6890F0, #4A90E2)',
        grass: 'linear-gradient(135deg, #78C850, #5CB85C)',
        electric: 'linear-gradient(135deg, #F8D030, #FFD700)',
        flying: 'linear-gradient(135deg, #A890F0, #9575CD)',
        bug: 'linear-gradient(135deg, #A8B820, #8BC34A)',
        poison: 'linear-gradient(135deg, #A040A0, #8E24AA)',
        normal: 'linear-gradient(135deg, #A8A878, #9E9E9E)',
        fighting: 'linear-gradient(135deg, #C03028, #E53935)',
        ground: 'linear-gradient(135deg, #E0C068, #D4B836)',
        rock: 'linear-gradient(135deg, #B8A038, #A67C00)',
        psychic: 'linear-gradient(135deg, #F85888, #EC407A)',
        ghost: 'linear-gradient(135deg, #705898, #7E57C2)',
        ice: 'linear-gradient(135deg, #98D8D8, #4DD0E1)',
        dragon: 'linear-gradient(135deg, #7038F8, #5E35B1)',
        dark: 'linear-gradient(135deg, #705848, #5D4E37)',
        steel: 'linear-gradient(135deg, #B8B8D0, #90A4AE)',
        fairy: 'linear-gradient(135deg, #EE99AC, #F06292)',
      },
      iconColorMap: {
        normal: '#F5F5DC',
        fire: '#FFE4B5',
        water: '#E0F6FF',
        grass: '#F0FFF0',
        electric: '#FFFACD',
        ice: '#F0FFFF',
        fighting: '#FFE4E1',
        poison: '#E6E6FA',
        ground: '#FFF8DC',
        flying: '#F0F8FF',
        psychic: '#FFF0F5',
        bug: '#F5FFFA',
        rock: '#FDF5E6',
        ghost: '#F8F8FF',
        dragon: '#F0F8FF',
        dark: '#F5F5F5',
        steel: '#F8F8FF',
        fairy: '#FFF0F5',
      },

      pokemonImageMap: {
        normal:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/133.png', // Eevee
        fire: 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/4.png', // Charmander
        water:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/7.png', // Squirtle
        grass:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/1.png', // Bulbasaur
        electric:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/25.png', // Pikachu
        ice: 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/361.png', // Snorunt
        fighting:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/56.png', // Mankey
        poison:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/88.png', // Grimer
        ground:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/104.png', // Cubone
        flying:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/21.png', // Spearow
        psychic:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/63.png', // Abra
        bug: 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/10.png', // Caterpie
        rock: 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/95.png', // Onix
        ghost:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/92.png', // Gastly
        dragon:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/147.png', // Dratini
        dark: 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/198.png', // Murkrow
        steel:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/81.png', // Magnemite
        fairy:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/35.png', // Clefairy
      },
    }
  },
}
</script>

<style scoped>
@keyframes pulse-glow {
  0% {
    box-shadow: 0 0 10px 4px rgba(255, 255, 255, 0.3);
  }
  50% {
    box-shadow: 0 0 20px 8px rgba(255, 255, 255, 0.6);
  }
  100% {
    box-shadow: 0 0 10px 4px rgba(255, 255, 255, 0.3);
  }
}

.q-card.glow-effect {
  animation: pulse-glow 2s infinite ease-in-out;
}
</style>
