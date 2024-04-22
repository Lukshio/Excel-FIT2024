<script setup>
import { onMounted, ref } from 'vue'
import { zoneStore } from '@/main'

defineProps({
  zone: Object,
  temp: Object,
  showDetails: Boolean
})

const finished = ref(false)
const confirmDialog = ref(false)
const emit = defineEmits(['editZone', 'deleteZone'])

function emitEdit (zone) {
  emit('editZone', zone)
}

function emitDeleteZone (zone) {
  emit('deleteZone', zone)
}
function confirmAH (zone) {
  zoneStore.switchAutoHeating(zone)
  confirmDialog.value = false
}

onMounted(() => {
  finished.value = true
})
</script>
<template>
  <v-card
    v-if="finished"
    elevation="2"
    class="my-3 zone_card"
  >
    <v-dialog
      max-width="450"
      v-model="confirmDialog">
      <v-card class="px-10">
        <v-card-text class="text-center">
          {{zone.autoHeating ? $t('globals.off') : $t('globals.on')}} {{ $t('zones.switchAutoHeating') }}
        </v-card-text>
        <v-card-actions class="text-center justify-space-between">
          <v-btn variant="text" color="red" @click="confirmDialog = false">{{ $t('globals.cancelBtn') }}</v-btn>
          <v-btn variant="tonal" color="green" @click="confirmAH(zone)">{{ $t('globals.confirmBtn') }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <template v-slot:title>{{zone.name}}</template>
    <template v-slot:append>
      <v-icon v-if="zone.autoHeating" color="green-darken-2" @click="confirmDialog = true">mdi-power-settings</v-icon>
      <v-icon v-else color="red-accent-4" @click="confirmDialog = true" >mdi-power-settings</v-icon>

      <v-icon v-if="zone.useProgram" color="green-darken-2">mdi-clock-check-outline</v-icon>
      <v-icon v-else color="red-accent-4">mdi-clock-remove-outline</v-icon>

      <v-icon v-if="!zone.autoHeating" color="red-darken-4">mdi-hand-pointing-up</v-icon>
      <v-icon v-else-if="zone.currentlyHeating === 'on'" color="orange-darken-4">mdi-fire</v-icon>
      <v-icon v-else-if="zone.currentlyHeating === 'off'" color="indigo-darken-4">mdi-snowflake</v-icon>
      <v-icon v-else-if="zone.currentlyHeating === 'fireplace'" color="red-darken-4">mdi-fireplace</v-icon>

      <v-icon>{{ zone.icon }}</v-icon>

      <v-icon v-if="showDetails" color="red-darken-4" style="z-index: 1050" @click="emitDeleteZone(zone)">mdi-delete</v-icon>
    </template>
    <v-card-text @click="emitEdit(zone)" style="cursor:pointer;">
      <v-row>
        <v-col cols="7" style="padding-right: 0; font-weight: 500">
          <div v-if="showDetails"><v-icon>mdi-thermometer-auto</v-icon> {{ zone.temperature }} <v-icon size="small">mdi-temperature-celsius</v-icon></div>
          <div v-else class="card-temp"><v-icon color="tertiary">mdi-thermometer-auto</v-icon> {{ zone.temperature }} <v-icon size="small">mdi-temperature-celsius</v-icon></div>
          <div v-if="showDetails"><v-icon>mdi-electric-switch</v-icon> {{ zone.relayName }}</div>
          <div v-if="showDetails"><v-icon>mdi-thermometer-probe</v-icon> {{ zone.sensor?.name }}</div>
          <div v-if="showDetails"><v-icon>mdi-google-analytics</v-icon> {{ zone.kpi }}</div>
        </v-col>
        <v-col cols="5" style="padding-left: 0">
          <div class="card-temp-div d-flex align-center">
            <v-icon color="tertiary">mdi-thermometer</v-icon>
            <span v-if="temp" class="card-temp"> {{ temp.temperature.toFixed(1) }} </span>
            <span v-else-if="zone.sensor === null" class="card-temp"> <v-icon :size="20" color="red-darken-4">mdi-close</v-icon> </span>
            <v-progress-circular v-else indeterminate :size="16" color="primary"></v-progress-circular>
          </div>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<style scoped>
.v-card, :deep(.v-card-title), .v-card-text{
  font-family: 'Lexend',serif;
  color: rgb(var(--v-theme-tertiary)) !important;
  font-weight: 600;
}
.zone_card{
  background-color: rgb(var(--v-theme-primaryBgGradFirst));
  background-image: linear-gradient(62deg, rgb(var(--v-theme-primaryBgGradFirst)) 0%, rgb(var(--v-theme-primaryBgGradSecond)) 100%);
}
.card-temp{
  display: flex;
  align-items: center;
  color: rgb(var(--v-theme-tertiary)) !important;
  font-weight: 500;
  font-size: 1.5em;
}
.card-temp-div{
  font-size: 1.5em;
}
:deep(.v-card-item__append){
  padding-inline-start: 0;
}
</style>
