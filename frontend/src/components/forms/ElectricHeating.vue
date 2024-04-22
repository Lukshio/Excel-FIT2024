<script setup>

import { computed, ref, watchEffect } from 'vue'
import { heatingStore } from '@/main'
import { useLocale } from 'vuetify'
import emitter from '@/plugins/eventBus'
import LoadingComponent from '@/components/LoadingComponent.vue'

const { t } = useLocale()

const btnVisible = ref(false)
const mode = ref(computed(() => heatingStore.storeElHeating))
const finishedLoading = ref(computed(() => heatingStore.elHeating.cached))
const data = ref(null)
function updateElHeat () {
  heatingStore.updateElHeating({ id: +data.value })
    .then(() => {
      emitter.emit('saved')
      btnVisible.value = false
    })
    .catch(() => {
      emitter.emit('general-err', 'Error')
    })
}

watchEffect(() => {
  data.value = mode.value
})
</script>

<template>
  <v-card class="ma-4" density="compact" v-if="finishedLoading">
    <v-card-text>
      <v-switch
        v-model="data"
        color="primary"
        @change="btnVisible = true"
      >
        <template v-slot:label>
          {{ $t('electricHeating') }}
          <v-icon :color=" data ? 'primary' : ''">mdi-lightning-bolt</v-icon>
        </template>
      </v-switch>
      <v-btn
        v-if="btnVisible"
        color="primary"
        variant="outlined"
        @click="updateElHeat()"
      > {{ $t('globals.confirmBtn') }}
      </v-btn>
    </v-card-text>
  </v-card>
  <LoadingComponent v-else></LoadingComponent>
</template>

<style scoped>

</style>
