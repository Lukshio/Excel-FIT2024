<script setup>
import { deviceStore, globalStore, zoneStore } from '@/main'
import ZoneForm from '@/components/forms/ZoneForm.vue'
import DeviceForm from '@/components/forms/DeviceForm.vue'
import { ref } from 'vue'
import emitter from '@/plugins/eventBus'
import { useLocale } from 'vuetify'
import { useRoute } from 'vue-router'
import router from '@/router'
const { t } = useLocale()
const route = useRoute()

const dialog = ref(false)

function insertZone (data) {
  zoneStore.insertNewZone(data)
    .then(() => {
      dialog.value = false
    }).catch(() => {
      emitter.emit('general-error', t('errors.duplicateZone'))
    })
}

function insertDevice (data) {
  const _data = {
    ...data,
    heatingSource: +data.heatingSource,
    heatingWaterSensor: +data.heatingWaterSensor,
    overheatProtection: +data.overheatProtection,
    fireplaceOpen: +data.fireplaceOpen
  }
  deviceStore.insertDevice(_data)
    .then(() => {
      dialog.value = false
    }).catch(() => {
      emitter.emit('general-error', t('errors.duplicateDevice'))
    })
}

</script>

<template>
  <div>
    <v-app-bar
      id="appbar"
      density="compact"
      flat
      v-if="route.name !== 'login'"
    >
      <div v-if="globalStore.getAppbarHeading === ''">
        <v-btn prepend-icon="mdi-arrow-left" color="white" @click="router.go(-1)">Zpět</v-btn>
      </div>
      <div class="d-flex justify-space-between w-auto ma-auto" v-else>
        <div class="mx-8" id="appbar_heading">{{ globalStore.getAppbarHeading }}</div>
        <v-divider vertical color="white" thickness="3" v-if="globalStore.getIsVisibleButton"></v-divider>
        <v-btn
          class="mx-8"
          variant="flat"
          @click="dialog = true"
          v-if="globalStore.getIsVisibleButton"
          color="navigation"
        >
          {{ globalStore.app.button.text }}
        </v-btn>
      </div>
    </v-app-bar>
    <v-dialog
      v-model="dialog"
      max-width="900"
    >
      <v-card
        class="pa-5">
        <ZoneForm @on-submit="insertZone" v-if="globalStore.app.button.routeName === 'zoneCreate'"></ZoneForm>
        <DeviceForm @on-submit="insertDevice" v-if="globalStore.app.button.routeName === 'deviceCreate'"></DeviceForm>
      </v-card>
    </v-dialog>
  </div>
</template>

<style scoped>
#appbar {
  background-color: rgb(var(--v-theme-primaryBgGradFirst));
  background-image: linear-gradient(180deg, rgb(var(--v-theme-primaryNavbarBgGradFirst)) 0%, rgb(var(--v-theme-primaryNavbarBgGradSecond)) 100%);
}
#appbar_heading {
  font-weight: 400;
  font-family: 'Lexend', serif;
  font-size: 1.4em;
  color: white;
}
</style>
