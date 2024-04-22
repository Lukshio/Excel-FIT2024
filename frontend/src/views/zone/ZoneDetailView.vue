<template>
  <v-container>
    <LoadingComponent v-if="!finishedLoading || updating"></LoadingComponent>
    <ZoneForm v-else :zone-data="zoneData" @on-submit="updateZone"></ZoneForm>
  </v-container>
</template>

<script setup>
import { computed, ref, watchEffect } from 'vue'
import ZoneForm from '@/components/forms/ZoneForm.vue'
import LoadingComponent from '@/components/LoadingComponent.vue'
import router from '@/router'
import { useRoute } from 'vue-router'
import { zoneStore } from '@/main'
import emitter from '@/plugins/eventBus'

// Data
const route = useRoute()
const zoneData = ref(computed(() => zoneStore.storeSingleZone(+route.params.id)))
const finishedLoading = ref(computed(() => !!zoneData.value))
const updating = ref(false)

watchEffect(() => {
  if (!zoneData.value) {
    zoneStore.getAllZones()
  }
})
function updateZone (formData) {
  updating.value = true
  const data = {
    ...formData,
    autoHeating: +formData.autoHeating,
    useProgram: +formData.useProgram
  }
  zoneStore.updateZone(data)
    .then(() => {
      router.push({ name: 'zoneIndex' })
      emitter.emit('saved')
    })
    .catch(() => {
      updating.value = false
    })
}
</script>
<style scoped>

</style>
