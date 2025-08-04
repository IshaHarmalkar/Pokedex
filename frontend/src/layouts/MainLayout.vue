<template>
  <q-layout view="lhh Lpr lFf" class="bg-grey-1 test-grey-9">
    <q-header class="q-ma-sm q-ma-md-md q-ma-lg-lg">
      <q-toolbar class="rounded-borders q-mb-md">
        <q-toolbar-title class="goldman-bold flex items-center">
          <router-link
            :to="{ name: 'pokedex' }"
            class="row items-center no-wrap text-primary"
            style="text-decoration: none"
          >
            <q-icon
              name="catching_pokemon"
              :size="$q.screen.lt.sm ? 'sm' : $q.screen.lt.md ? 'md' : 'lg'"
              class="q-mr-sm"
            />
            <span v-if="$q.screen.gt.xs">Pokedex</span>
          </router-link>
        </q-toolbar-title>

        <q-space />

        <!-- Desktop/Tablet Navigation with labels -->
        <q-btn-group v-if="$q.screen.gt.xs" flat class="pokemon-nav">
          <q-btn
            flat
            :to="{ name: 'pokedex' }"
            icon="list_alt"
            :label="$q.screen.gt.sm ? 'Pokemon' : ''"
            class="nav-btn"
            :size="$q.screen.lt.md ? 'sm' : 'md'"
          />
          <q-btn
            flat
            :to="{ name: 'compare' }"
            icon="compare"
            :label="$q.screen.gt.sm ? 'Compare' : ''"
            class="nav-btn"
            :size="$q.screen.lt.md ? 'sm' : 'md'"
          />
          <q-btn
            flat
            :to="{ name: 'random' }"
            icon="shuffle"
            :label="$q.screen.gt.sm ? 'Random' : ''"
            class="nav-btn"
            :size="$q.screen.lt.md ? 'sm' : 'md'"
          />
          <q-btn
            flat
            :to="{ name: 'TypesPage' }"
            icon="category"
            :label="$q.screen.gt.sm ? 'Types' : ''"
            class="nav-btn"
            :size="$q.screen.lt.md ? 'sm' : 'md'"
          />

          <!-- <q-btn
            flat
            :to="{ name: 'test' }"
            icon="yard"
            :label="$q.screen.gt.sm ? 'Test' : ''"
            class="nav-btn"
            :size="$q.screen.lt.md ? 'sm' : 'md'"
          /> -->
        </q-btn-group>

        <!-- Mobile Navigation - icons only -->
        <div v-else class="row q-gutter-xs">
          <q-btn
            flat
            round
            :to="{ name: 'pokedex' }"
            icon="list_alt"
            size="sm"
            class="mobile-nav-btn"
          />
          <q-btn
            flat
            round
            :to="{ name: 'compare' }"
            icon="compare"
            size="sm"
            class="mobile-nav-btn"
          />
          <q-btn
            flat
            round
            :to="{ name: 'random' }"
            icon="shuffle"
            size="sm"
            class="mobile-nav-btn"
          />
          <q-btn
            flat
            round
            :to="{ name: 'TypesPage' }"
            icon="category"
            size="sm"
            class="mobile-nav-btn"
          />
          <!-- <q-btn flat :to="{ name: 'test' }" icon="yard" size="sm" class="mobile-nav-btn" /> -->
        </div>
      </q-toolbar>
    </q-header>

    <q-page-container class="q-pa-sm q-pa-md-md q-pa-lg-lg">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
export default {
  name: 'MainLayout',
}
</script>

<style lang="scss" scoped>
.q-toolbar {
  border-radius: 12px;
  min-height: 56px;
}

.q-header {
  border-bottom: 1px solid #e0e0e0;
  color: $primary;
  background-color: white;
}

.pokemon-nav {
  .nav-btn {
    padding: 8px 16px;
    margin: 0 2px;
    border-radius: 8px;
    transition: all 0.2s ease;

    &:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    &.router-link-active {
      background-color: rgba(255, 255, 255, 0.2);
      font-weight: 600;
    }
  }
}

.mobile-nav-btn {
  border-radius: 8px;
  transition: all 0.2s ease;

  &:hover {
    background-color: rgba(255, 255, 255, 0.1);
  }

  &.router-link-active {
    background-color: rgba(255, 255, 255, 0.2);
  }
}

// Use Quasar's responsive typography
.q-toolbar-title {
  font-size: 1.25rem;

  @media (min-width: 600px) {
    font-size: 1.5rem;
  }

  @media (min-width: 1024px) {
    font-size: 1.75rem;
  }
}
</style>
