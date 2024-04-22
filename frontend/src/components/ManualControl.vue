<template>
  <v-container>
    <v-card>
      <v-alert v-if="globalStore.storeOperationalMode === 1 || globalStore.storeOperationalMode === 3" variant="outlined" color="primary" type="warning" class="alert_box">
        <span v-if="full">{{ $t('manual.alert') }}</span>
        <span v-else>Auto</span>
      </v-alert>
    </v-card>
    <div v-if="firstLoad">
      <v-card v-for="(relay, index) in relays" :key="index" class="d-flex align-self-center align-center my-3">
        <v-switch
          class="ml-5"
          hide-details
          v-model="relay.gpioState"
          :label="relay.name"
          :loading="switchLoading"
          color="secondary"
          :disabled="relay.disabled || (relay.heatingSource === 1 && auto) || switchLoading"
          @click="switchRelay(relay)"
        >
        </v-switch>
        <v-alert v-if="relay.disabled || (relay.heatingSource === 1 && auto)" variant="outlined" color="orange" type="warning" class="alert_box">
          <span v-if="full">{{ $t('manual.alert') }}</span>
          <span v-else>Auto</span>
        </v-alert>
      </v-card>
      <v-card>
        <v-alert
          v-if="error.show"
          variant="outlined"
          type="error">
          {{ $t('manual.error') }}<br><br>
          {{ error.msg }}
        </v-alert>
      </v-card>
    </div>
    <LoadingComponent v-else></LoadingComponent>
  </v-container>
</template>

<script setup>

import { computed, ref, watchEffect } from 'vue'
import {deviceStore, globalStore, heatingStore, manualStore} from '@/main'
import LoadingComponent from '@/components/LoadingComponent.vue'
const full = ref(computed(() => window.innerWidth > 900))
const finishedLoading = ref(false)
const auto = ref(false)
const relays = ref([])
const status = ref(null)
const switchLoading = ref(true)
const firstLoad = ref(false)
const error = ref({
  show: false,
  msg: null
})

function switchRelay (relay) {
  switchLoading.value = true
  manualStore.switchRelay(relay)
    .then(loadData)
    .finally(() => {
      switchLoading.value = false
    })
}

async function loadData () {
  try {
    const activeRelays = await deviceStore.storeActiveRelays
    const pinouts = activeRelays.map(relay => relay.pinout)
    status.value = await manualStore.getGpioStatus(pinouts)
      .catch((e) => {
        error.value.show = true
        error.value.msg = e.message
      })

    relays.value = activeRelays.map((relay) => {
      const gpioState = !status.value[relay.pinout]
      if (relay.zoneRelay?.autoHeating &&
        (heatingStore.storeOperationalMode[0].name === 'gas_boiler' ||
          heatingStore.storeOperationalMode[0].name === 'combined')) auto.value = true
      console.log(heatingStore.storeOperationalMode.name)
      return {
        ...relay,
        gpioState,
        disabled: (!!relay.zoneRelay?.autoHeating &&
          (heatingStore.storeOperationalMode[0].name === 'combined' ||
            heatingStore.storeOperationalMode[0].name === 'gas_boiler')) || false
      }
    })
  } catch (error) {
    console.error(error)
  } finally {
    firstLoad.value = true
    switchLoading.value = false
  }
}

// finishedLoading.value = computed(() => {
//   if (deviceStore.storeActiveRelays && heatingStore.storeOperationalMode)
//     return true
// }).value

watchEffect(() => {
  if (deviceStore.storeActiveRelays && heatingStore.storeOperationalMode) finishedLoading.value = true
  if (finishedLoading.value) {
    loadData()
  }
})
</script>

<style scoped>
@media screen and (max-width: 900px) {
  .alert_box {
    padding-left: 0;
    padding-right: 0;
  }
}
</style>
