import { defineStore } from 'pinia'

export const useSearchStore = defineStore('search', {
  state: () => ({
    searchQuery: '',
    searchResults: [],
    lastMode: 'null', //search, pokedex
    lastPageData: [],
    lastPaginatedUrl: '',
  }),

  actions: {
    setSearch(query, results) {
      this.searchQuery = query
      this.searchResults = results
      this.lastMode = 'search'
    },

    resetSearch() {
      this.searchQuery = ''
      this.searchResults = []
      this.lastMode = 'pokedex'
    },
    setLastPage(data, url) {
      this.lastPageData = data
      this.lastPaginatedUrl = url
    },
  },

  persist: true,
})
