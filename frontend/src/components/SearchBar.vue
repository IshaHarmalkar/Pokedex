<template>
  <div class="q-mx-md search-bar-container">
    <q-input
      v-model="searchQuery"
      filled
      debounce="300"
      placeholder="Name or Number"
      @keyup.enter="onSearch"
      @clear="onClear"
      clearable
      dense
      outlined
      class="search-bar"
    >
      <template v-slot:append>
        <q-icon name="search" @click="onSearch" class="cursor-pointed icon" />
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

<style lang="scss" scoped>
.search-bar {
  background-color: #f6fafb;
}

.icon {
}
</style>
