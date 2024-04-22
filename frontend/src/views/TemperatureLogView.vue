<script setup>
import { computed, ref } from 'vue'
import { globalStore, heatingStore } from '@/main'
import LoadingComponent from '@/components/LoadingComponent.vue'
import { useLocale } from 'vuetify'

const { t } = useLocale()
globalStore.setAppbarHeading(t('tempLog.heading'))
const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Source', key: 'sensorUuid' },
  { title: 'Value', key: 'value' },
  { title: 'Created at', key: 'createdAt' }
]
const logs = ref(computed(() => heatingStore.storeTemperatureLogs))
const loading = ref(computed(() => !!logs.value))
// const groupBy = [{ key: 'type' }]

</script>
<template>
  <section>
    <v-data-table
      v-if="loading"
      :items="logs"
      :headers="headers"
    >
      <template v-slot:item.sensorUuid="{ item }">
        {{ item.sensor ? item.sensor.name : item.sensorUuid }}
      </template>
    </v-data-table>
    <LoadingComponent v-else></LoadingComponent>
  </section>
</template>

<style scoped>

</style>
