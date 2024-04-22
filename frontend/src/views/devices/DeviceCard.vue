<script setup>
import { ref } from 'vue'
import { deviceStore } from '@/main'

defineProps({
  device: Object
})

const dialog = ref(false)
const deleteDevice = ref(null)

const emit = defineEmits(['editDevice'])

function emitEdit (device) {
  emit('editDevice', device)
}
function deleteDeviceFunction (device) {
  dialog.value = true
  deleteDevice.value = device
}
function deleteDeviceConfirm (device) {
  deviceStore.deleteDevice(device.id)
  deviceStore.getDevices()
  dialog.value = false
}

</script>

<template>
  <div>
    <v-card
      elevation="2"
    >
      <template v-slot:title>{{device.name}}</template>
      <template v-slot:append>
        <v-icon style="z-index: 1050" @click="emitEdit(device)">mdi-pencil</v-icon>
        <v-icon style="z-index: 1050" color="red-darken-4" @click="deleteDeviceFunction(device)">mdi-delete</v-icon>
      </template>
      <v-card-text>
        <div>{{ $t('devices.form.description') }}: {{device.deviceType.description}}</div>
      </v-card-text>
    </v-card>
    <v-dialog
      max-width="500"
      v-model="dialog"
    >
      <v-card>
        <v-card-text>{{ $t('devices.deleteForm.text', { msg: deleteDevice.name })}}</v-card-text>
        <v-card-actions class="justify-center">
          <v-btn color="red" @click="dialog = false">{{ $t('globals.cancelBtn')}}</v-btn>
          <v-btn
            variant="tonal"
            color="green"
            @click="deleteDeviceConfirm(deleteDevice)"
          >{{ $t('globals.confirmBtn')}}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<style scoped>

.v-card, :deep(.v-card-title), .v-card-text{
  font-family: 'Lexend',serif;
  color: rgb(var(--v-theme-tertiary)) !important;
  font-weight: 600;
}
.v-card{
  background-color: rgb(var(--v-theme-primaryBgGradFirst));
  background-image: linear-gradient(62deg, rgb(var(--v-theme-primaryBgGradFirst)) 0%, rgb(var(--v-theme-primaryBgGradSecond)) 100%);
}

</style>
