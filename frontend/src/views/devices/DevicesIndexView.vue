<template>
  <v-container fluid class="px-0 py-0">
    <LoadingComponent v-if="!finishedLoading" ></LoadingComponent>
    <div v-else>
      <v-tabs align-tabs="center" v-model="tab" bg-color="navigation">
        <v-tab value="sensors">{{ $t('devices.tabs.sensors') }}</v-tab>
        <v-tab value="relays">{{ $t('devices.tabs.relays') }}</v-tab>
        <v-btn
          @click="findDS18B20"
          color="primary"
          variant="outlined"
          class="mt-2"
          :loading="sensorsLoading"
        >{{ $t('devices.ds18b20')}}
        </v-btn>
      </v-tabs>
      <div class="mt-1">
        <v-window v-model="tab">
          <v-window-item value="sensors">
            <v-container>
              <v-row fluid>
                <v-col v-for="(sensor, index) in sensors" :key="index" cols="12" sm="6" md="4" xl="3">
                  <DeviceCard :device="sensor" @editDevice="editDeviceFunction(sensor)"></DeviceCard>
                </v-col>
              </v-row>
            </v-container>
          </v-window-item>
          <v-window-item value="relays">
            <v-container>
              <v-row fluid>
                <v-col v-for="(relay, index) in relays" :key="index" cols="12" sm="6" md="4" xl="3">
                  <DeviceCard :device="relay" v-if="relays !== null" @editDevice="editDeviceFunction(relay)"></DeviceCard>
                </v-col>
              </v-row>
            </v-container>
          </v-window-item>
        </v-window>
      </div>
    </div>
    <v-dialog
      max-width="900"
      v-model="editDialog">
      <v-card>
        <v-card-text class="v-card-title text-center font-weight-bold pt-6">
          <v-icon class="float-right" @click="editDialog = false">mdi-window-close</v-icon>
        </v-card-text>
        <DeviceForm :device-data="editDevice" @on-submit="updateDevice"></DeviceForm>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>

import { useDeviceStore } from '@/stores/deviceStore'
import LoadingComponent from '@/components/LoadingComponent.vue'
import { computed, ref, watchEffect } from 'vue'
import DeviceCard from '@/views/devices/DeviceCard.vue'
import { globalStore } from '@/main'
import { useLocale } from 'vuetify'
import DeviceForm from '@/components/forms/DeviceForm.vue'
import deviceApi from '@/api/deviceApi'
import router from '@/router'

const { t } = useLocale()
globalStore.setAppbarHeading(t('devices.heading'))
globalStore.setAppButton({
  visible: true,
  text: 'Přidat',
  routeName: 'deviceCreate'
})

const sensorsLoading = ref(false)
const finishedLoading = ref(false)
const sensors = ref(null)
const relays = ref(null)
const tab = ref('sensors')

const deviceStore = useDeviceStore()

const devices = ref(computed(() => deviceStore.storeDevices))
const editDevice = ref(null)
const editDialog = ref(false)
function editDeviceFunction (device) {
  editDevice.value = device
  editDialog.value = true
}

// aktualizace dat
function updateDevice (formData) {
  finishedLoading.value = false
  const data = {
    ...formData,
    heatingSource: +formData.heatingSource,
    heatingWaterSensor: +formData.heatingWaterSensor,
    overheatProtection: +formData.overheatProtection,
    fireplaceOpen: +formData.fireplaceOpen
  }
  deviceStore.updateDevice(data)
    .then(() => {
      editDialog.value = false
    })
    .catch(() => {
      finishedLoading.value = true
    })
}
function findDS18B20 () {
  sensorsLoading.value = true

  deviceApi.findDS18B20()
    .then(() => {
      this.emitter.emit('saved-alert')
      router.go(0)
    }).catch(() => {
      sensorsLoading.value = false
    })
}
watchEffect(() => {
  sensors.value = devices.value.filter(device => device.deviceType.name === 'temperature_sensor')
  relays.value = devices.value.filter(device => device.deviceType.name === 'relay')
  finishedLoading.value = true
})
</script>

<style scoped>

</style>
