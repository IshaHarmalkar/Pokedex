<template>
  <div class="flex flex-center">
    <q-card
      class="pokemon-card goldman-regular"
      :style="{
        boxShadow: `0 6px 15px ${primaryTypeColor}`,
      }"
    >
      <!-- Image Section -->
      <q-card-section
        class="hero-section relative-position"
        :style="{ background: `linear-gradient(135deg, ${primaryTypeColor} 0%), #a8e060 100%` }"
      >
        <div class="text-h4 row goldman-bold text-white">
          {{ pokemon.name.charAt(0).toUpperCase() + pokemon.name.slice(1) }}
        </div>

        <div class="pokemon-types flex">
          <q-chip
            v-for="type in typedColors"
            :key="type.name"
            square
            clickable
            :style="{
              border: `2px solid ${type.color}`,
              backgroundColor: 'rgba(255, 255, 255, 0.15)', // subtle backdrop
              color: 'white',
              fontWeight: '600',
              boxShadow: `0 0 6px ${type.color}66`,
            }"
            >{{ type.name }}</q-chip
          >
          <div class="flex-left">
            <q-img
              :src="pokemon.official_artwork_url"
              fit="contain"
              style="width: 150px; height: 150px"
            />
          </div>
        </div>
      </q-card-section>

      <q-card-section>
        <div class="pokemon-description text-center">
          {{ pokemon.description }}
        </div>
      </q-card-section>

      <!-- Battle Stats -->

      <q-card-section>
        <div class="text-h6 text-center">Battle Stats</div>
        <div>
          <div class="row justify-center q-gutter-md">
            <!-- HP -->
            <div class="column items-center">
              <q-circular-progress
                show-value
                size="50px"
                :thickness="0.22"
                rounded
                color="lime"
                track-color="grey-3"
                class="q-ma-md"
                :value="pokemon.stats.hp"
              />
              <div class="batt-stats">HP</div>
            </div>

            <!-- Attack -->
            <div class="column items-center">
              <q-circular-progress
                show-value
                size="50px"
                :thickness="0.22"
                rounded
                color="lime"
                track-color="grey-3"
                class="q-ma-md"
                :value="pokemon.stats.attack"
              />
              <div class="batt-stats">Attack</div>
            </div>

            <!-- Defense -->

            <div class="column items-center">
              <q-circular-progress
                show-value
                size="50px"
                :thickness="0.22"
                rounded
                color="lime"
                track-color="grey-3"
                class="q-ma-md"
                :value="pokemon.stats.defense"
              />
              <div class="batt-stats">Defense</div>
            </div>

            <!-- Special Attack -->
            <div class="column items-center">
              <q-circular-progress
                show-value
                size="50px"
                :thickness="0.22"
                rounded
                color="lime"
                track-color="grey-3"
                class="q-ma-md"
                :value="pokemon.stats.special_attack"
              />
              <div class="batt-stats">Special Attack</div>
            </div>

            <!-- Special Defense -->
            <div class="column items-center">
              <q-circular-progress
                show-value
                size="50px"
                :thickness="0.22"
                rounded
                color="lime"
                track-color="grey-3"
                class="q-ma-md"
                :value="pokemon.stats.special_defense"
              />
              <div class="batt-stats">Special Defense</div>
            </div>

            <!-- Speed -->
            <div class="column items-center">
              <q-circular-progress
                show-value
                size="50px"
                :thickness="0.22"
                rounded
                color="lime"
                track-color="grey-3"
                class="q-ma-md"
                :value="pokemon.stats.speed"
              />
              <div class="batt-stats">Speed</div>
            </div>
          </div>
        </div>
      </q-card-section>

      <!--  Base Stats -->

      <q-card-section class="q-pa-none">
        <div class="row text-h6 justify-center">Base Stats</div>
        <div
          class="base-stat-container"
          :style="{ background: `linear-gradient(135deg, ${primaryTypeColor} 0%), #a8e060 100%` }"
        >
          <div class="row">
            <div class="col-4 base-stat-type text-center">
              <div>{{ pokemon.height }}</div>
              <div class="caption">Height</div>
            </div>

            <div class="col-4 base-stat-type text-center border-lr">
              <div>{{ pokemon.weight }}</div>
              <div class="caption">Weight</div>
            </div>

            <div class="col-4 base-stat-type text-center">
              <div>{{ pokemon.base_experience }}</div>
              <div class="caption">Base Experience</div>
            </div>
          </div>
        </div>
      </q-card-section>

      <q-card-section class="q-ma-none q-pa-none evolution-section">
        <!-- Evolution Button -->
        <div class="evolution" v-if="hasEvolution">
          <q-card-actions>
            <q-btn flat label="Evolution" color="grey" @click="expanded = !expanded" />
            <q-space />
            <q-btn
              color="grey"
              round
              flat
              dense
              :icon="expanded ? 'keyboard_arrow_up' : 'keyboard_arrow_down'"
              @click="expanded = !expanded"
            />
          </q-card-actions>

          <q-slide-transition class="">
            <div v-show="expanded">
              <q-separator />
              <q-card-section>
                <!-- <div class="text-h6 text-center q-mb-md">{{ evolutionChain.chain_name }}</div> -->

                <div
                  class="evolution-container"
                  :style="{
                    background: `linear-gradient(135deg, ${primaryTypeColor}ee, ${primaryTypeColor}bb)`,
                  }"
                >
                  <div class="row justify-center items-center q-gutter-sm">
                    <div
                      v-for="(evolution, index) in evolutionChain.evolution_chain"
                      :key="evolution.name"
                      class="evolution-item"
                    >
                      <!-- Evolution Stage -->
                      <div class="text-center">
                        <div class="evolution-name text-h6 text-capitalize text-white">
                          {{ evolution.name }}
                        </div>
                        <div class="evolution-order text-caption text-white">
                          Stage {{ evolution.order }}
                        </div>
                      </div>

                      <!-- Arrow between evolutions (except for last item) -->
                      <q-icon
                        v-if="index < evolutionChain.evolution_chain.length - 1"
                        name="arrow_forward"
                        class="evolution-arrow text-white"
                        size="md"
                      />
                    </div>
                  </div>
                </div>
              </q-card-section>
            </div>
          </q-slide-transition>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
export default {
  name: 'TestCard',

  props: {
    pokemon: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      loading: false,
      error: null,
      value: 34,
      evolutionChain: [],
      activeTab: 'stats',
      expanded: false,
      showEvolutionDialog: false,

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

  computed: {
    typedColors() {
      return (
        this.pokemon.types?.map((type) => ({
          name: type,
          color: this.typeColors[type] || '#ccc',
        })) || []
      )
    },
    // hasEvolution() {
    //   return (
    //     Array.isArray(this.evolutionChain.evolution_chain) &&
    //     this.evolutionChain.evolution_chain.length > 1
    //   )
    // },

    hasEvolution() {
      return (
        this.evolutionChain &&
        this.evolutionChain.evolution_chain &&
        Array.isArray(this.evolutionChain.evolution_chain) &&
        this.evolutionChain.evolution_chain.length > 0 &&
        !this.evolutionChain.message
      )
    },

    primaryTypeColor() {
      return this.typedColors[0]?.color || '#ccc'
    },

    gradientFromTypes() {
      const colors = this.typedColors.map((type) => type.color)
      if (colors.length === 0) return '#ccc'
      if (colors.length === 1) return colors[0]

      const gradientStops = colors.map((c, i) => `${c} ${(i / (colors.length - 1)) * 100}%`)
      return `linear-gradient(135deg, ${gradientStops.join(', ')})`
    },
  },

  mounted() {
    this.fetchEvolutionChain()
  },

  methods: {
    async fetchEvolutionChain() {
      this.loading = true
      try {
        console.log('Inside evolution function')
        const res = await this.$api.get(`/pokemon/evolution/${this.pokemon.name.toLowerCase()}`)
        this.evolutionChain = res.data || []

        //  console.log(this.evolutionChain)
      } catch (err) {
        console.error('Error fetching evolution chain', err)
        this.error = 'Failed to load evolution data'
        this.evolutionChain = [] // Ensure it's always an array
      } finally {
        this.loading = false
      }
    },
    onClick(type) {
      // Handle type chip click - maybe emit event or navigate
      this.$emit('type-clicked', type)
    },
  },
}
</script>

<style lang="scss" scoped>
.pokemon-card {
  //box-shadow: 0 6px 15px #78c850;
  padding: 0;
}
.hero-section {
  //background: linear-gradient(135deg, #78c850 0%, #a8e060 100%);
}

.batt-stats {
  color: #65707f;
}
.q.chip {
  border: solid 1px $grass;
}

.caption {
  color: white;
}

.base-stat-container {
  // background-color: #78c850;
}

.base-state {
}

.base-stat-type {
  //   border-right: solid 1px;
  padding: 2px 0px 2px 4px;
}

.border-lr {
  border-left: 1px solid black;
  border-right: 1px solid black;
}
.scroll-x {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  padding-bottom: 8px;
}

//evoltuion
.evolution-container {
  border-radius: 8px;
  padding: 16px;
  // margin-top: 8px;
}

.evolution-item {
  display: flex;
  align-items: center;
  gap: 12px;
}

.evolution-name {
  font-weight: 600;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

.evolution-order {
  opacity: 0.9;
}

.evolution-arrow {
  margin: 0 8px;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}
</style>
