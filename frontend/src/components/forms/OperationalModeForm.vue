<script setup>

import { computed, ref, watchEffect } from 'vue'
import { heatingStore } from '@/main'
import { useLocale } from 'vuetify'
import emitter from '@/plugins/eventBus'
const { t } = useLocale()
import { rules } from '@/composable/formRules'
import LoadingComponent from '@/components/LoadingComponent.vue'

function mapMode (data) {
  if (data === null) return
  return data.map(m => {
    if (m.name === 'fireplace') m.visibleText = t('operationalMode.krb')
    if (m.name === 'gas_boiler') m.visibleText = t('operationalMode.kotel')
    if (m.name === 'turned_off') m.visibleText = t('operationalMode.off')
    if (m.name === 'combined') m.visibleText = t('operationalMode.combined')
    return m
  })
}

const finishedLoading = ref(false)
const newItems = ref(computed(() => mapMode(heatingStore.storeHeatingModes)))
const actualMode = ref(computed(() => mapMode(heatingStore.storeOperationalMode)))

const dialog = ref(false)
const mode = ref(null)

watchEffect(() => {
  if (actualMode.value && newItems.value) {
    finishedLoading.value = true
    mode.value = actualMode.value
  }
})
function updateOperationalMode () {
  if (mode.value === null)
    emitter.emit('general-error', 'Ve formuláři chybí data')
  else {
    heatingStore.updateOperationalMode({"id": +mode.value})
      .then(() => {
        emitter.emit('saved')
        dialog.value = false
      }).catch(() => {
      emitter.emit('general-err', 'Error')
    })
  }
}
</script>

<template>
  <v-card class="ma-4" v-if="finishedLoading">
    <v-card-title> {{ actualMode[0].visibleText }}
      <v-icon v-if="heatingStore.storeOperationalMode[0].name === 'fireplace' || heatingStore.storeOperationalMode[0].name === 'combined'" color="primary">mdi-fireplace</v-icon>
      <v-icon v-if="heatingStore.storeOperationalMode[0].name === 'combined'|| heatingStore.storeOperationalMode[0].name === 'gas_boiler'" color="primary">mdi-gas-burner</v-icon>
      <v-icon v-if="heatingStore.storeOperationalMode[0].name === 'turned_off'">mdi-fireplace-off</v-icon>
    </v-card-title>
    <v-card-subtitle>{{ $t('operationalMode.title') }}</v-card-subtitle>
    <v-card-actions>
      <v-btn
        @click="dialog = true"
        color="primary"
        variant="outlined"
        append-icon="mdi-pencil"
      > {{ $t('operationalMode.change')}}
      </v-btn>
    </v-card-actions>
  </v-card>
  <LoadingComponent v-else></LoadingComponent>
  <v-dialog
    v-model="dialog"
    max-width="350"
  >
    <v-form @submit.prevent @submit="updateOperationalMode">
      <v-card>
        <v-card-title>{{ $t('operationalMode.change')}}</v-card-title>
        <v-card-text>
          <v-select
            :items="newItems"
            item-title="visibleText"
            item-value="id"
            :rules="[rules.required]"
            v-model="mode"
          >
          </v-select>
        </v-card-text>
        <v-card-actions>
          <v-btn
            type="submit"
            @submit="updateOperationalMode"
          >
            {{ $t('globals.saveBtn')}}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </v-dialog>
</template>

<style scoped>

</style>
