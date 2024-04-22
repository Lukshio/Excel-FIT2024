<template>
  <v-snackbar
    v-model="snackbar"
    :timeout="3000"
    :color="snackbarColor"
  >
    {{ errText1stLine }}<br>
    {{ errText2ndLine }}
    <template v-slot:actions>
      <v-btn
        color="white"
        variant="text"
        @click="snackbar = false"
      >
        Close
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script setup>
import { ref } from 'vue'
import emitter from '@/plugins/eventBus'

const snackbar = ref(false)
const snackbarColor = ref('red')
const errText1stLine = ref(null)
const errText2ndLine = ref(null)

emitter.on('api-error', e => err(e))
emitter.on('saved', saved)
emitter.on('general-error', generalErr)

function generalErr (error) {
  snackbarColor.value = 'red-darken-1'
  snackbar.value = true
  errText1stLine.value = error
  errText2ndLine.value = ''
}
function err (error) {
  snackbarColor.value = 'red-darken-1'
  snackbar.value = true

  errText1stLine.value = error.message
  errText2ndLine.value = error.response.data.message || error.response.data.error
}

function saved () {
  snackbarColor.value = 'light-green-darken-1'
  snackbar.value = true
  errText1stLine.value = 'Uloženo'
  errText2ndLine.value = ''
}

</script>

<style scoped>

</style>
