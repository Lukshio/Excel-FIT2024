<template>
  <v-container fluid>
    <LoadingComponent v-if="!finishedLoading"></LoadingComponent>
      <v-data-table
        v-else
        :items="localData"
        :headers="headers"
        item-value="name"
      >
        <template v-slot:item.value="{ item }">
          <v-text-field
            class="dt_input"
            :step="item.name === 'offset' ? 0.1 : 1"
            density="compact"
            hide-details
            v-model="item.value"
          >
            <template v-slot:prepend>
              <v-icon
                color="primary"
                @click="item.value--"
              >mdi-minus
              </v-icon>
            </template>
            <template v-slot:append>
              <v-icon
                color="primary"
                @click="item.value++"
              >mdi-plus
              </v-icon>
            </template>
          </v-text-field>
        </template>
      </v-data-table>
    <ElectricHeating></ElectricHeating>
    <OperationalModeForm></OperationalModeForm>
    <div
      class="w-100 d-flex justify-end">
      <v-btn
        class="mt-5"
        color="primary"
        variant="tonal"
        prepend-icon="mdi-content-save-outline"
        @click="globalStore.saveSettings(localData)"
      >
        {{ $t('globals.saveBtn') }}
      </v-btn>
    </div>
    <v-btn
      @click="globalApi.testTempLogs()"
    >
      Test temp log
    </v-btn>
    <v-btn
      @click="globalApi.testAutoHeating()">
      Test auto heating
    </v-btn>

    <v-btn
      @click="globalApi.testWaterSwitch()">
      Test water switch
    </v-btn>

    <v-btn
      @click="globalApi.testWaterHeating()">
      Test water heating
    </v-btn>

    <v-btn
      @click="globalApi.testOther()">
      Test other
    </v-btn>
  </v-container>
</template>

<script setup>
import { globalStore } from '@/main'
import { computed, ref, watchEffect } from 'vue'
import LoadingComponent from '@/components/LoadingComponent.vue'
import globalApi from '@/api/globalApi'
import ElectricHeating from '@/components/forms/ElectricHeating.vue'
import OperationalModeForm from '@/components/forms/OperationalModeForm.vue'

const headers = [
  { title: 'Description', key: 'description' },
  { title: 'Item', key: 'name' },
  { title: 'Hodnota', key: 'value' }
]

const localData = ref(computed(() => JSON.parse(JSON.stringify(globalStore.storeSettings))))
const finishedLoading = ref(false)

watchEffect(() => {
  if (localData.value) finishedLoading.value = true
})
</script>

<style scoped>
.dt_input {
  min-width: 150px;
}
</style>
