<script setup>
import { onMounted, ref, watchEffect } from 'vue'
import LoadingComponent from '@/components/LoadingComponent.vue'
import { deviceStore } from '@/main'
import emitter from '@/plugins/eventBus'
import { rules } from '@/composable/formRules'

const props = defineProps({
  deviceData: {
    type: Object
  }
})

const form = ref({
  id: props.deviceData?.id || null,
  uuid: props.deviceData?.uuid || null,
  pinout: props.deviceData?.pinout || null,
  name: props.deviceData?.name || null,
  protocolId: props.deviceData?.protocolId || null,
  description: props.deviceData?.description || null,
  deviceTypeId: props.deviceData?.deviceTypeId || null,
  heatingSource: !!props.deviceData?.heatingSource || null,
  heatingWaterSensor: !!props.deviceData?.heatingWaterSensor || null,
  overheatProtection: !!props.deviceData?.overheatProtection || null,
  fireplaceOpen: !!props.deviceData?.fireplaceOpen || null
})

const finishedLoading = ref(false)

onMounted(() => {
  finishedLoading.value = true
})

const emit = defineEmits(['on-submit'])
function validateForm () {
  if (
    form.value.name === null || form.value.name === '' ||
    form.value.uuid === null || form.value.uuid === '' ||
    form.value.deviceTypeId === null
  ) emitter.emit('general-error', 'Ve formuláři chybí data')
  else emit('on-submit', form.value)
}

const disableHeatSource = ref(false)
const disablePinout = ref(false)
const disableHeatWaterSensor = ref(true)
const disableSubmit = ref(false)
const disableOHP = ref(true)
const disableFO = ref(true)

function heatingSource () {
  const type = deviceStore.storeFindDeviceType(form.value.deviceTypeId)
  if (type.name === 'relay') {
    disableHeatSource.value = false
    disableFO.value = false
    // disablePinout.value = false

    form.value.overheatProtection = false
    form.value.heatingWaterSensor = false
    if (form.value.fireplaceOpen && form.value.heatingSource) form.value.heatingSource = false
  }
  else {
    disableHeatSource.value = true
    form.value.heatingSource = false
    disableFO.value = true
  }
  if (type.name === 'temperature_sensor') {
    // disablePinout.value = true
    form.value.pinout = null
    form.value.fireplaceOpen = false
    disableHeatWaterSensor.value = false
    disableOHP.value = false
  } else {
    disableHeatWaterSensor.value = true
    disableOHP.value = true
    // disablePinout.value = false
    form.value.heatingWaterSensor = false
  }
}

function invalidCombination () {
  const type = deviceStore.storeFindDeviceType(form.value.deviceTypeId)
  const protocol = deviceStore.storeFindProtocolType(form.value.protocolId)
  if (!protocol || !type) return
  disableSubmit.value = (type.name === 'relay' && protocol.name === '1wire') ||
    (type.name === 'temperature_sensor' && protocol.name === 'gpio')
  if (protocol.name !== 'gpio') {
    disablePinout.value = true
    form.value.pinout = null
  } else disablePinout.value = false
}

const editableHS = ref(false)
const editableWS = ref(false)
const editableOHP = ref(false)
const editableFO = ref(false)

watchEffect(() => {
  if (form.value.deviceTypeId) heatingSource()
})

onMounted(() => {
  finishedLoading.value = true
  if (form.value.heatingSource) editableHS.value = true
  if (form.value.heatingWaterSensor) editableWS.value = true
  if (form.value.overheatProtection) editableOHP.value = true
  if (form.value.fireplaceOpen) editableFO.value = true
})

</script>

<template>
  <v-form @submit.prevent @submit="validateForm">
    <h1 class="text-center" style="font-size: 1.2em"> {{form.name}}</h1>
    <v-container>
      <span class="my-3" style="color:#909090; font-size: 0.8em">{{ $t('globals.requiredForm') }}</span>
      <v-row v-if="finishedLoading">
        <v-col cols="12" md="6" xl="4">
          <v-text-field
            class="required"
            variant="solo-filled"
            v-model="form.name"
            :label="$t('devices.form.name')"
            :rules="[rules.required]"
          >
          </v-text-field>
        </v-col>
        <v-col cols="12" md="6" xl="4">
          <v-text-field
            variant="solo-filled"
            class="required"
            v-model="form.uuid"
            :label="$t('devices.form.uuid')"
            :rules="[rules.required]"
          >
          </v-text-field>
        </v-col>
        <v-col cols="12" sm="6" md="6" xl="4">
          <v-select
            variant="solo-filled"
            hide-selected
            class="required"
            :items="deviceStore.storeDeviceTypes"
            :disabled="!deviceStore.storeDeviceTypes.length"
            :loading="!deviceStore.storeDeviceTypes.length"
            :rules="[rules.required]"
            @update:menu="invalidCombination"
            item-value="id"
            item-title="description"
            :label="$t('devices.form.deviceType')"
            v-model="form.deviceTypeId">
          </v-select>
        </v-col>
        <v-col cols="12" sm="6" md="6" xl="4">
          <v-select
            variant="solo-filled"
            :items="deviceStore.storeProtocols"
            :loading="!deviceStore.storeProtocols.length"
            :disabled="!deviceStore.storeProtocols.length"
            item-value="id"
            item-title="text"
            v-model="form.protocolId"
            @update:menu="invalidCombination"
            class="required"
            :rules="[rules.required]"
            :label="$t('devices.form.protocol')"
          >
          </v-select>
        </v-col>
        <v-col cols="12" sm="6" md="6" xl="4">
          <v-text-field
            variant="solo-filled"
            v-model="form.pinout"
            type="number"
            :disabled="disablePinout"
            min="1"
            :label="$t('devices.form.pin')"
          >
          </v-text-field>
        </v-col>
        <v-col cols="12" sm="6" md="6" xl="4">
          <v-text-field
            variant="solo-filled"
            v-model="form.description"
            :label="$t('devices.form.description')"
          >
          </v-text-field>
        </v-col>
        <v-col sm="6" md="6" xl="4">
          <v-switch
            color="primary"
            hide-details
            density="compact"
            v-model="form.heatingSource"
            :disabled="form.fireplaceOpen === true"
            v-if="(!deviceStore.storeBoilerRelayPresent || editableHS) && !disableHeatSource"
            :label="$t('devices.form.heatingSource')"
          ></v-switch>
          <v-alert
            v-else-if="!disableHeatSource && deviceStore.storeBoilerRelayPresent "
            variant="outlined"
            type="info"
            density="compact"
          >
            {{ $t('devices.form.heatingSourcePresent') }}
          </v-alert>
          <v-switch
            color="primary"
            hide-details
            density="compact"
            v-model="form.fireplaceOpen"
            v-if="!disableFO"
            :disabled="form.heatingSource === true"
            :label="$t('devices.form.fireplaceOpen')"
          ></v-switch>
          <v-switch
            color="primary"
            hide-details
            density="compact"
            v-model="form.heatingWaterSensor"
            v-if="(!deviceStore.storeHeatingWaterSensorPresent || editableWS) && !disableHeatWaterSensor"
            :disabled="disableHeatWaterSensor"
            :label="$t('devices.form.heatingWaterSensor')"
          ></v-switch>
          <v-alert
            v-else-if="!disableHeatWaterSensor && deviceStore.storeHeatingWaterSensorPresent || editableWS"
            variant="outlined"
            type="info"
            density="compact"
          >
            {{ $t('devices.form.heatingWaterSensorPresent') }}
          </v-alert>
          <v-switch
            color="primary"
            hide-details
            density="compact"
            v-model="form.overheatProtection"
            v-if="(!deviceStore.storeOverHeatPresent || editableOHP) && !disableOHP"
            :disabled="disableOHP"
            :label="$t('devices.form.overHeat')"
          ></v-switch>
          <v-alert
            v-else-if="!disableOHP && deviceStore.storeOverHeatPresent || editableOHP"
            variant="outlined"
            type="info"
            density="compact"
          >
            {{ $t('devices.form.overheatPresent') }}
          </v-alert>
        </v-col>
        <v-col sm="6" md="6" xl="4">
          <v-btn
            :disabled="disableSubmit"
            type="submit"
            block
            @submit="form"
          >{{ $t('globals.saveBtn')}}
          </v-btn>
          <v-alert
            class="mt-2"
            variant="outlined"
            type="warning"
            density="compact"
            v-if="disableSubmit"
          >
            {{ $t('devices.form.invalidCombination') }}
          </v-alert>
        </v-col>
      </v-row>
      <LoadingComponent v-else></LoadingComponent>
    </v-container>
  </v-form>
</template>

<style scoped>

</style>
