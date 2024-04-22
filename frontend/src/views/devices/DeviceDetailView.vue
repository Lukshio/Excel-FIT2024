<script setup>
import {computed, onMounted, ref, watchEffect} from 'vue'
import { useRoute } from 'vue-router'
import router from '@/router'
import DeviceForm from '@/components/forms/DeviceForm.vue'
import LoadingComponent from '@/components/LoadingComponent.vue'
import { deviceStore } from '@/main'
import emitter from '@/plugins/eventBus'

const finishedLoading = ref(computed(() => !!deviceData.value))
const updating = ref(false)
const route = useRoute()
const deviceData = ref(computed(() => deviceStore.storeSingleDevice(+route.params.id)))

watchEffect(() => {
  if (!deviceData.value){
    deviceStore.getDevices()
  }
})

function updateDevice (formData) {
  updating.value = true
  const data = {
    ...formData,
    heatingSource: +formData.heatingSource
  }
  deviceStore.updateDevice(data)
    .then(() => {
      router.push({ name: 'deviceIndex' })
      emitter.emit('saved')
    })
    .catch(() => {
      updating.value = false
    })
}

</script>

<template>
  <div>
    <LoadingComponent v-if="!finishedLoading || updating"></LoadingComponent>
    <DeviceForm v-else :device-data="deviceData" @on-submit="updateDevice"></DeviceForm>
  </div>
</template>

<style scoped>

</style>
