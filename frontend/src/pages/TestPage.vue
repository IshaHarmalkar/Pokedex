<template>
  <q-page>
    <div class="q-ma-lg">
      <test-card />
    </div>
  </q-page>
</template>

<script>
import TestCard from 'src/components/TestCard.vue'

export default {
  name: 'TestPage',
  components: {
    TestCard,
  },
}
</script>

<style lang="scss" scoped></style>
