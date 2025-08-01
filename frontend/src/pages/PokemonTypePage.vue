<template>
  <q-page class="q-pa-md">
    <div class="text-h3 q-mb-md capitalize text-center">{{ type }}</div>

    <div class="row q-col-gutter-md">
      <PokemonCard
        v-for="pokemon in pokemons"
        :key="pokemon.id"
        :pokemon="pokemon"
        class="col-12 col-sm-6 col-md-4 col-lg-3"
      />
    </div>

    <div class="q-my-md flex flex-center" v-if="loading">
      <q-spinner color="primary" />
    </div>

    <q-infinite-scroll @load="loadMore" :offset="100" ref="infiniteScrollRef" />
  </q-page>
</template>

<script>
import { api } from 'boot/axios'
import PokemonCard from 'components/PokemonCard.vue'

export default {
  name: 'PokemonTypePage',
  components: { PokemonCard },
  data() {
    return {
      type: this.$route.params.type,
      pokemons: [],
      lastId: 0,
      limit: 20,
      hasMore: true,
      loading: false,
    }
  },
  methods: {
    async loadMore(done) {
      if (!this.hasMore || this.loading) return

      this.loading = true
      try {
        const res = await api.get(`/pokemon/type/${this.type}`, {
          params: {
            limit: this.limit,
            last_id: this.lastId,
          },
        })

        this.pokemons.push(...res.data.data)
        this.lastId = res.data.next_last_id
        this.hasMore = res.data.has_more
      } catch (err) {
        console.error(err)
        this.hasMore = false
      } finally {
        this.loading = false
        if (done) done()
      }
    },
  },
}
</script>

<style scoped>
.capitalize {
  text-transform: capitalize;
}
</style>
