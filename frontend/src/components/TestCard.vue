<template>
  <div class="flex flex-center">
    <q-card class="pokemon-card">
      <!-- Image Section -->
      <q-card-section class="hero-section">
        <div class="text-h4 row">Bulbasaur</div>
        <div class="pokemon-types flex">
          <q-chip square clickable @click="onClick"> Grass </q-chip>
          <q-chip square clickable @click="onClick"> Poison </q-chip>
          <div class="flex-left">
            <q-img
              src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/1.png"
              fit="contain"
              style="width: 150px; height: 150px"
            />
          </div>
        </div>
      </q-card-section>

      <q-card-section>
        <div class="pokemon-description">
          A strange seed was planted on its back at birth. The plant sprouts and grows with this
          POKéMON.
        </div>
      </q-card-section>

      <!-- Battle Stats -->

      <q-card-section>
        <div class="text-h6 text-center">Battle Stats</div>
        <div>
          <div>
            <!-- HP -->

            <q-circular-progress
              show-value
              size="50px"
              :thickness="0.22"
              rounded
              color="lime"
              track-color="grey-3"
              class="q-ma-md"
              value="45"
            />

            <!-- Attack -->
            <q-circular-progress
              show-value
              size="50px"
              :thickness="0.22"
              rounded
              color="lime"
              track-color="grey-3"
              class="q-ma-md"
              value="49"
            />

            <!-- Defense -->

            <q-circular-progress
              show-value
              size="50px"
              :thickness="0.22"
              rounded
              color="lime"
              track-color="grey-3"
              class="q-ma-md"
              value="49"
            />

            <!-- Special Attack -->
            <q-circular-progress
              show-value
              size="50px"
              :thickness="0.22"
              rounded
              color="lime"
              track-color="grey-3"
              class="q-ma-md"
              value="65"
            />

            <!-- Special Defense -->
            <q-circular-progress
              show-value
              size="50px"
              :thickness="0.22"
              rounded
              color="lime"
              track-color="grey-3"
              class="q-ma-md"
              value="65"
            />

            <!-- Speed -->
            <q-circular-progress
              show-value
              size="50px"
              :thickness="0.22"
              rounded
              color="lime"
              track-color="grey-3"
              class="q-ma-md"
              value="45"
            />
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
export default {
  name: 'TestCard',

  data() {
    return {
      pokemon: null,
      loading: false,
      error: null,
      value: 34,

      //Pokemon Type colors
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
}
</script>

<style lang="scss" scoped>
.hero-section {
  background: linear-gradient(135deg, #78c850 0%, #a8e060 100%);
}

.q.chip {
  border: solid 1px $grass;
}
</style>
