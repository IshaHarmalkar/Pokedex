import { defineStore } from 'pinia'

export const useNavigationStore = defineStore('navigate', {
  state: () => ({
    PageNo: 1, //initially it's 1
    mode: 'pokedex', //it can be pokedex or search
    lastPageBeforeSearch: 1,
    searchQuery: '',
  }),

  actions: {
    setPage(pageNo) {
      this.PageNo = pageNo
    },

    nextPage() {
      this.PageNo++
    },

    prevPage() {
      if (this.PageNo > 1) {
        this.PageNo--
      }
    },

    enterSearchMode(query) {
      this.lastPageBeforeSearch = this.PageNo
      this.mode = 'search'
      this.searchQuery = query
    },

    exitSearchMode() {
      this.mode = 'pokedex'
      this.PageNo = this.lastPageBeforeSearch
      this.searchQuery = ''
    },

    reset() {
      this.pageNo = 1
      this.prevPage = null
      this.mode = 'pokedex'
      this.lastPageBeforeSearch = 1
      this.searchQuery = ''
    },
  },

  persist: true,
})
