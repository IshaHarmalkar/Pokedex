<template>
  <q-layout view="lhh Lpr lFf" class="bg-grey-1 test-grey-9">
    <q-header class="q-ma-sm rounded-borders q-ma-lg">
      <q-toolbar class="bg-primary text-white rounded-borders q-mb-md">
        <q-toolbar-title>Pokedex</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script></script>

<style lang="scss" scoped>
.q-toolbar {
  border-radius: 12px;
}

.q-header {
  border-bottom: 1px solid #e0e0e0;
}
</style>
