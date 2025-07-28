<template>
  <div class="q-gutter-md row items-end q-md-md">
    <div class="col-12 col-sm-5">
      <q-input v-model="first" label="First Pokemon" outlined dense @keyup.enter="emitCompare" />
    </div>

    <div class="col-12 col-sm-5">
      <q-input v-model="second" label="Second Pokemon" outlined dense @keyup.enter="emitCompare" />
    </div>

    <div class="col-12 col-sm-2">
      <q-btn label="Compare" color="primary" @click="emitCompare" :disable="!first || !second" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'PokemonCompareInput',
  props: {
    modelFirst: String,
    modelSecond: String,
  },

  emits: ['compare'],

  data() {
    return {
      first: this.modelFirst || '',
      second: this.modelSecond || '',
    }
  },

  methods: {
    emitCompare() {
      this.$emit('compare', {
        first: this.first.trim().toLowerCase(),
        second: this.second.trim().toLowerCase(),
      })
    },
  },
}
</script>

<style lang="scss" scoped></style>
