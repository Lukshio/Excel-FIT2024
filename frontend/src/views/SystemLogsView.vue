<script setup>
import { computed, ref } from 'vue'
import { globalStore } from '@/main'
import LoadingComponent from '@/components/LoadingComponent.vue'
import { useLocale } from 'vuetify'

const { t } = useLocale()
globalStore.setAppbarHeading(t('systemLog.heading'))

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Source', key: 'name' },
  { title: 'Type', key: 'type' },
  { title: 'Text', key: 'text' },
  { title: 'Created', key: 'createdAt' }
]

const logs = ref(computed(() => globalStore.storeSystemLogs))
const loading = ref(computed(() => !!logs.value))
const groupBy = [{ key: 'type' }]

</script>
<template>
  <section>
    <v-data-table
      :items="logs"
      :headers="headers"
      v-if="loading"
      :group-by="groupBy"
      item-key="id"
    >
    </v-data-table>
    <LoadingComponent v-else></LoadingComponent>
  </section>
</template>

<style scoped>

</style>
