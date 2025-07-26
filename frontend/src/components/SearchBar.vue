<template>
  <div class="q-mb-md">
    <q-input
      v-model="searchQuery"
      filled
      debounce="300"
      placeholder="Search by name or id"
      @keyup.enter="onSearch"
      @clear="onClear"
      clearable
      dense
      rounded
      outlined
    >
      <template v-slot:append>
        <q-icon name="search" @click="onSearch" class="cursor-pointed" />
      </template>
    </q-input>
  </div>
</template>

<script>
export default {
  name: 'SearchBar',

  props: {
    initialQuery: String,
  },
  data() {
    return {
      searchQuery: this.initialQuery || '',
    }
  },

  methods: {
    onSearch() {
      if (this.searchQuery.trim() == '') return
      this.$emit('search', this.searchQuery.trim())
    },

    onClear() {
      this.searchQuery = ''
      this.$emit('search-cleared')
    },
  },
}
</script>

<style lang="scss" scoped></style>
