<script setup>

import { computed, ref } from 'vue'
import { globalStore, zoneStore } from '@/main'
import LoadingComponent from '@/components/LoadingComponent.vue'
import { useZoneStore } from '@/stores/zoneStore'

globalStore.setAppbarHeading('Teploty')

const zStore = useZoneStore()

const currentTemps = ref(computed(() => zStore.storeCurrentTemps))
const loadingFinished = ref(computed(() => zStore.currentTemps.cached))

</script>
<template>
  <v-container fluid>
    <v-btn
      prepend-icon="mdi-reload"
      @click="zoneStore.getCurrentTemps()"
      :loading="!loadingFinished"
      color="primary"
      variant="tonal"
      class="mb-4"
    >Obnovit
    </v-btn>
    <v-row v-if="loadingFinished">
      <v-col v-for="(item, index) in currentTemps" :key="index" sm="12" md="6" lg="4" xl="3">
        <v-card>
          <template v-slot:title>{{ item.sensor.name }}</template>
          <template v-slot:append>
            <div class="d-flex align-center">
              <v-icon style="font-size: 2em" color="primary">mdi-thermometer</v-icon>
              <span class="card-temp">{{ item.temperature }} &#176;C </span>
            </div>
          </template>
          <template v-slot:subtitle>{{ item.uuid }}</template>
        </v-card>
      </v-col>
    </v-row>
    <LoadingComponent v-else></LoadingComponent>
  </v-container>
</template>

<style scoped>
.v-card, :deep(.v-card-title), .v-card-text{
  font-family: 'Lexend',serif;
  font-weight: 600
}

.card-temp{
  color: rgb(var(--v-theme-primary)) !important;
  font-weight: 200;
  font-size: 1.6em;
}
.card-temp-div{
  font-size: 1.7em;
}

</style>
