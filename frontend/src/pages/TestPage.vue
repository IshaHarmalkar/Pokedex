<template>
  <q-page>
    <div class="q-ma-lg">
      <test-card :pokemon="pokemonData" />
    </div>
  </q-page>
</template>

<script>
import TestCard from 'src/components/TestCard.vue'

export default {
  name: 'TestPage',
  components: {
    TestCard,
  },

  data() {
    return {
      pokemonData: {
        id: 10,
        name: 'caterpie',
        pokedex_number: 10,
        types: ['bug'],
        stats: {
          hp: 45,
          attack: 30,
          defense: 35,
          special_attack: 20,
          special_defense: 20,
          speed: 45,
        },
        sprite_url:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/10.png',
        official_artwork_url:
          'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/10.png',
        description:
          'Its short feet are tipped with suction pads that enable it to tirelessly climb slopes and walls.',
        height: '0.30',
        weight: '2.90',
        base_experience: 39,
        created_at: '2025-07-30T18:18:12.000000Z',
        updated_at: '2025-07-30T18:18:12.000000Z',
      },
      hasEvolution: false,
    }
  },
}
</script>

<style lang="scss" scoped></style>
