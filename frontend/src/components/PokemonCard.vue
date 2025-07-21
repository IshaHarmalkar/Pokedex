<template>
  <div @click="$emit('card-clicked', pokemon)" style="cursor: pointer">
    <q-card
      class="q-ma-sm q-pa-sm rounded-borders cursor-pointer"
      flat
      bordered
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
            color="primary"
            text-color="white"
            dense
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
}
</script>

<style lang="scss" scoped>
.rounded-border {
  border-radius: 16px;
}
</style>
