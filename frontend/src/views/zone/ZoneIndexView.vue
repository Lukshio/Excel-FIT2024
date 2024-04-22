<template>
  <div>
    <v-container v-if="finishedLoading" fluid>
      <v-switch
        v-if="userStore.userData.role === 'admin'"
        v-model="globalStore.getVisibleZoneDetails"
        @click="globalStore.switchZoneDetails()"
        :label="$t('globals.showDetails')"
        color="primary"
        hide-details
        density="compact"
      >
      </v-switch>
      <v-row class="d-flex">
        <v-col v-for="(zone, index) in localZones" :key="index" cols="12" xs="12" md="6" lg="4" xl="3" >
          <ZoneCard
            :zone="zone"
            :temp="currentTemps[zone.mainSensorUuid]"
            @deleteZone="deleteZoneFunction(zone)"
            @editZone="editZoneFunction(zone)"
            :showDetails="globalStore.getVisibleZoneDetails"
          ></ZoneCard>
        </v-col>
      </v-row>
      <v-dialog
        max-width="500"
        v-model="deleteDialog"
      >
        <v-card class="px-10 delete_dialog">
          <v-card-text class="text-center font-weight-bold">{{ $t('zones.deleteForm.text', { msg: deleteZone.name })}}</v-card-text>
          <v-card-actions class="justify-space-between">
            <v-btn color="red" variant="tonal" @click="deleteDialog = false">{{ $t('globals.cancelBtn') }}</v-btn>
            <v-btn
              variant="text"
              color="green"
              @click="confirmDelete(deleteZone.id)"
              :loading="deleteLoading">{{ $t('globals.confirmBtn') }}</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog
        max-width="900"
        v-model="editDialog"
      >
        <v-card class="pb-10">
          <v-card-text class="pt-3 pb-4"><v-icon @click="editDialog = false" class="float-right">mdi-window-close</v-icon></v-card-text>
          <ZoneForm :zone-data="editZone" @on-submit="updateZone"></ZoneForm>
        </v-card>
      </v-dialog>
    </v-container>
    <LoadingComponent v-else ></LoadingComponent>
  </div>
</template>

<script setup>
import { computed, ref, watchEffect } from 'vue'
import LoadingComponent from '@/components/LoadingComponent.vue'
import { globalStore, zoneStore, userStore } from '@/main'
import ZoneCard from '@/views/zone/ZoneCard.vue'
import ZoneForm from '@/components/forms/ZoneForm.vue'
import emitter from '@/plugins/eventBus'
import { useLocale } from 'vuetify'
const { t } = useLocale()
globalStore.setAppbarHeading(t('zones.heading'))
globalStore.setAppButton({
  visible: userStore.userData.role === 'admin',
  text: 'Přidat',
  routeName: 'zoneCreate'
})
// Data
const deleteDialog = ref(false)
const showDetails = ref(true)
const deleteZone = ref(null)
const deleteLoading = ref(false)

const editDialog = ref(false)
const editZone = ref(null)

const localZones = ref(computed(() => zoneStore.storeAllZones))
const currentTemps = ref(computed(() => zoneStore.storeCurrentTemps))

const finishedLoading = ref(false)

// cekani na nacteni dat
watchEffect(() => {
  if (localZones.value && currentTemps.value) finishedLoading.value = true
})

// Methods
function editZoneFunction (zone) {
  editZone.value = zone
  editDialog.value = true
}

function updateZone (formData) {
  editDialog.value = false
  finishedLoading.value = false
  const data = {
    ...formData,
    autoHeating: +formData.autoHeating,
    useProgram: +formData.useProgram
  }
  zoneStore.updateZone(data)
    .then(() => {
      emitter.emit('saved')
    })
    .catch(() => {
      finishedLoading.value = true
    })
}
const deleteZoneFunction = (zone) => {
  deleteDialog.value = true
  deleteZone.value = zone
}

const confirmDelete = (id) => {
  deleteLoading.value = true
  zoneStore.deleteZone(id)
  deleteLoading.value = false
  deleteDialog.value = false
}
</script>

<style scoped>
:deep(.delete_dialog) {
  font-family: 'Lexend', serif;
  font-weight: 600 !important;
}
</style>
