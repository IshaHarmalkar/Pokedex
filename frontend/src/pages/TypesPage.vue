<template>
  <q-page class="q-pa-md">
    <div class="text-h3 text-center goldman-bold q-mb-lg text-secondary title">Pokemon Types</div>
    <div class="row q-gutter-md">
      <type-card v-for="type in types" :key="type" :type="type" @click="goToType(type)" />
    </div>
  </q-page>
</template>

<script>
import TypeCard from 'src/components/TypeCard.vue'

export default {
  name: 'TypesPage',
  components: {
    TypeCard,
  },

  data() {
    return {
      types: [
        'normal',
        'fire',
        'water',
        'grass',
        'electric',
        'ice',
        'fighting',
        'poison',
        'ground',
        'flying',
        'psychic',
        'bug',
        'rock',
        'ghost',
        'dragon',
        'dark',
        'steel',
        'fairy',
      ],
    }
  },

  methods: {
    goToType(type) {
      this.$router.push({ name: 'TypePage', params: { type } })
    },
  },
}
</script>

<style lang="scss" scoped>
.q-page {
}
.title {
}
</style>
