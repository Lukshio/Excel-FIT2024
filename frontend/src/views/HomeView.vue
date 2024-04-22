<script setup>
// import TemperaturesTable from '@/components/TemperaturesTable.vue'
import ZoneSelect from '@/views/zone/ZoneIndexView.vue'
import TemperaturesView from '@/views/TemperaturesView.vue'
import DevicesIndexView from '@/views/devices/DevicesIndexView.vue'
import OperationalModeForm from '@/components/forms/OperationalModeForm.vue'
import ElectricHeating from '@/components/forms/ElectricHeating.vue'
import { globalStore } from '@/main'
import { useLocale } from 'vuetify'
const { t } = useLocale()

globalStore.setAppbarHeading(t('navDrawer.home'))
</script>

<template>
  <main>
    <v-container v-if="userStore.userData.role === 'admin'">
      <v-row>
        <v-col sm="12" md="6">
          <OperationalModeForm></OperationalModeForm>
        </v-col>
        <v-col sm="12" md="6">
          <ElectricHeating></ElectricHeating>
        </v-col>
      </v-row>
    </v-container>
    <TemperaturesView></TemperaturesView>
    <ZoneSelect></ZoneSelect>
    <DevicesIndexView v-if="userStore.userData.role === 'admin'"></DevicesIndexView>
  </main>
</template>

<script setup>

</script>
