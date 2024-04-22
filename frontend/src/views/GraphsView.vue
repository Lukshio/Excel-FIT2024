<template>
  <v-container id="bg" fluid class="text-center">
    <div>
      <v-form>
        <v-row>
          <v-col cols="12" md="4">
            <v-menu
              v-model="dateMenu"
              :close-on-content-click="false"
            >
              <template v-slot:activator="{props}">
                <v-text-field
                  hide-details
                  density="compact"
                  label="Datum do:"
                  v-bind="props"
                  variant="outlined"
                  v-model="dateString"
                  readonly
                >
                </v-text-field>
              </template>
              <v-card>
                <VueDatePicker
                  v-model="date"
                  range
                  inline
                  auto-apply
                  :partial-range="false"
                >
                </VueDatePicker>
              </v-card>
            </v-menu>
          </v-col>
          <v-col cols="6" md="3">
            <v-select
              hide-details
              density="compact"
              v-model="type"
              :items="selectItems"
              hide-selected
              item-title="name"
              item-value="id"
              label="Typ"
              variant="outlined"
            >
            </v-select>
          </v-col>
          <v-col cols="6" md="3">
            <v-select
              hide-details
              density="compact"
              v-model="dataSelect"
              :items="dataItems"
              hide-selected
              item-title="name"
              :item-value="type === 0 ? 'uuid' : 'id'"
              label="Typ"
              variant="outlined"
            >
            </v-select>
          </v-col>
          <v-col cols="12" md="2">
            <v-btn
              color="primary"
              @click="getData"
              variant="tonal"
              block
            >Zobrazit</v-btn>
          </v-col>
        </v-row>
      </v-form>
    </div>
    <div id="chart" v-if="loaded">
      <apexchart type="line" height="350" :options="chartOptions" :series="chartOptions.series"></apexchart>
    </div>
  </v-container>
</template>

<script setup>
import { computed, ref } from 'vue'
import globalApi from '@/api/globalApi'
import { deviceStore, globalStore, zoneStore } from '@/main'

globalStore.setAppbarHeading('Grafy')

const dateMenu = ref(false)
const date = ref(null)
date.value = [
  new Date(new Date().setDate(new Date().getDate() - 7)),
  new Date()
]

// nastaveni rozmezi casu na lokalni format
const dateString = ref(computed(() =>
  date.value[0].toLocaleString('cs-CZ', { day: 'numeric', month: 'numeric', year: 'numeric' }).toString() + ' - ' +
  date.value[1].toLocaleString('cs-CZ', { day: 'numeric', month: 'numeric', year: 'numeric' }).toString()
))

const type = ref(null)
const dataSelect = ref(null)
const selectItems = [{
  id: 1,
  name: 'Zóny'
}, {
  id: 0,
  name: 'Zařízení'
}]

const dataItems = ref(computed(() => {
  if (type.value === 1) return zoneStore.storeAllZones
  else if (type.value === 0) return deviceStore.storeSensors
  else return []
}))

const chartOptions = {
  chart: {
    height: 350,
    type: 'line',
    zoom: {
      enabled: true
    }
  },
  grid: {
    row: {
      colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
      opacity: 0.5
    }
  },
  xaxis: {
    categories: []
  },
  series: [{
    name: 'Teplota',
    data: []
  }]
}
const loaded = ref(false)

// naparsovani dat pro zobrazeni
function parseGraphData (rawData) {
  rawData.forEach(item => {
    chartOptions.xaxis.categories.push(item.createdAt.split('T')[0]) // data x osa
    chartOptions.series[0].data.push(item.value) // hodnoty k datum
  })
  loaded.value = true
}

// zaslani a prijem dat
async function getData () {
  // reset dat
  loaded.value = false
  chartOptions.xaxis.data = []
  chartOptions.series[0].data = []
  // data k odeslani
  const data = {
    from: date.value[0].toISOString().split('T')[0],
    to: date.value[1].toISOString().split('T')[0],
    isZone: type.value,
    id: dataSelect.value
  }
  const graphData = await globalApi.getGraphData(data)
  parseGraphData(graphData)
}
</script>
<style scoped>
:deep(.dp__flex_display){
  display: initial;
}
</style>
