<template>
  <v-container v-if="!selectedZone || !finishedLoading">
    <h2>Výběr zóny</h2>
    <v-form>
      <v-row>
        <v-col>
          <v-select
            label="Výběr zóny"
            :items="localZones"
            item-title="name"
            item-value="id"
            v-model="selectedZone"
            :loading="selectedZone !== null"
            :disabled="selectedZone !== null"
          >
          </v-select>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
  <v-container style="overflow: hidden" v-else>
    <v-row>
      <v-col cols="12" md="6">
        <v-select
          label="Výběr zóny"
          variant="solo"
          color="primary"
          hide-details
          density="compact"
          :items="localZones"
          item-title="name"
          item-value="id"
          v-model="selectedZone"
          @update:modelValue="dialog = false"
        >
        </v-select>      </v-col>
      <v-col cols="12" md="6">
        <v-alert variant="outlined" density="compact" class="mb-5" type="warning" max-width="350">Nezapomeňte změny uložit</v-alert>
      </v-col>
    </v-row>
    <h1 class="text-h5 text-center mb-4">Zóna: {{zoneStore.storeSingleZone(selectedZone).name}}</h1>
    <v-item-group class="d-flex justify-center w-100">
      <div v-for="(day, index) in daysString" :key="index">
        <v-btn rounded class="mx-1" density="comfortable" @click="selectedDay = index" :color="selectedDay === index ? 'primary' : ''">{{ day }}</v-btn>
      </div>
    </v-item-group>
    <table id="schedule_table">
      <thead class="text-center">
      <tr>
        <th>Cas</th>
        <th v-for="item in header" :key="item" :colspan="colspan">{{item}}</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(value, index) in heatingDays" :key="index">
        <td>
          {{ daysString[index] }}
        </td>
        <td v-for="(time, index) in value" :key="index" :class="time === 1 ? 'heating' : ''">
          <span v-if="time === 1" class="heating"></span>
          <span v-else class="no_heating"></span>
        </td>
      </tr>
      </tbody>
    </table>
    <v-form class="mt-5">
      <div v-for="(schedule, index) in dailyEdit" :key="index">
        <v-row id="controls">
          <v-col cols="6">
            <v-text-field
              label="Od"
              type="number"
              variant="outlined"
              density="compact"
              min="0"
              max="22"
              readonly
              v-model="schedule[0]"
            >
              <template v-slot:prepend>
                <span>{{index}}</span>
              </template>
              <template v-slot:prepend-inner>
                <v-icon
                  @click="schedule[0] > 0 ? schedule[0]-- : ''"
                >
                  mdi-minus
                </v-icon>
              </template>
              <template v-slot:append-inner>
                <v-icon @click="schedule[0] < 23 && schedule[0] < schedule[1] - 1 ? schedule[0]++ : ''">
                  mdi-plus
                </v-icon>
              </template>
            </v-text-field>
          </v-col>
          <v-col cols="6">
            <v-text-field
              label="Do"
              density="compact"
              variant="outlined"
              v-model="schedule[1]"
              min="1"
              max="23"
              readonly
              type="number">
              <template v-slot:prepend-inner>
                <v-icon @click="schedule[1] > 0 && schedule[1] > schedule[0]+1 ? schedule[1]-- : ''">
                  mdi-minus
                </v-icon>
              </template>
              <template v-slot:append-inner>
                <v-icon @click="schedule[1] < 24? schedule[1]++ : ''">
                  mdi-plus
                </v-icon>
              </template>
              <template v-slot:append>
                <v-icon @click="removeDaily(index)">mdi-delete</v-icon>
              </template>
            </v-text-field>
          </v-col>
        </v-row>
      </div>
    </v-form>
    <v-row>
      <v-col cols="12" md="6">
        <v-btn @click="addNewDaily" variant="tonal" color="primary">Přidat rozvrh</v-btn>
      </v-col>
      <v-col cols="12" md="6">
        <v-btn @click="saveDailySchedule" variant="tonal" color="primary">Uložit celý program</v-btn>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { globalStore, zoneStore } from '@/main'
import { computed, onMounted, ref, watch, watchEffect } from 'vue'
import globalApi from '@/api/globalApi'
import emitter from '@/plugins/eventBus'

const finishedLoading = ref(false)
globalStore.setAppbarHeading('Program vytápění')
const localZones = ref(computed(() => zoneStore.storeAllZones))

const daysString = ['Po', 'Út', 'St', 'Čt', 'Pá', 'So', 'Ne']

const dialog = ref(false)
const selectedDay = ref(0)
const selectedZone = ref(null)
const dailyEdit = ref({})
const dataRaw = ref({})

// vynulovani pole
const heatingDays = ref({
  0: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  1: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  2: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  3: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  4: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  5: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
  6: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
})

// pro kazdy den se zpracuji data a v pripade zapnuti na nejakou hodinu se index reprezentujici hodinu nastavi na 1
function parseData () {
  Object.keys(dataRaw.value).forEach(day => {
    heatingDays.value[day] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
    Object.keys(dataRaw.value[day]).forEach(schedule => {
      const start = dataRaw.value[day][schedule][0]
      const end = dataRaw.value[day][schedule][1]

      for (let i = start; i <= end - 1; i++) {
        heatingDays.value[day][i] = 1
      }
    })
  })
}

// prijem dat
async function fetchData () {
  let fetchData = null
  // reset dat
  dataRaw.value = {
    0: {},
    1: {},
    2: {},
    3: {},
    4: {},
    5: {},
    6: {}
  }
  if (selectedZone.value !== null) {
    fetchData = await globalApi.getScheduleData(selectedZone.value)
    Object.keys(dataRaw.value).forEach(day => {
      if (fetchData[day]) {
        dataRaw.value[day] = Object.assign({}, fetchData[day])
      }
    })
  }
  parseData()
  finishedLoading.value = true
}

// ulozeni nastaveni
function saveDailySchedule () {
  const data = {
    zoneId: selectedZone.value,
    data: dataRaw.value
  }
  globalApi.updateScheduleData(data)
    .then(() => {
      emitter.emit('saved')
    })
    .catch(e => {
      console.error(e)
      emitter.emit('api-error', e)
    })
}

// aktualizace komponenty
function dailySchedule () {
  dailyEdit.value = dataRaw.value[selectedDay.value]
}

// pridani casoveho useku
function addNewDaily () {
  dataRaw.value[selectedDay.value][Object.keys(dataRaw.value[selectedDay.value]).length + 1] = ([0, 1])
  dailySchedule()
}

// ostraneni casoveho useku
function removeDaily (key) {
  delete dataRaw.value[selectedDay.value][key]
}

// Computed property, aktualizace pri zmene
onMounted(() => {
  parseData()
  dailySchedule()
})
watchEffect(() => {
  parseData()
  dailySchedule()
})
watch(selectedZone, () => {
  fetchData()
})
watch(selectedDay, () => {
  dailySchedule()
})


// responzivita
const header = ref(computed(() => {
  if (window.innerWidth > 900) {
    return [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]
  } else {
    return [8, 16, 23]
  }
}))
const colspan = ref(computed(() => {
  if (window.innerWidth > 900) {
    return 1
  } else {
    return 8
  }
}))
</script>

<style scoped>
@media only screen and (max-width: 900px) {
  :deep(.v-btn--size-default) {
    min-width: 0;
    padding: 0 4px;
  }
}
:deep(.v-input--horizontal .v-input__append, .v-input--horizontal .v-input__prepend) {
  margin-inline-start: 2px;
  margin-inline-end: 2px
}
#controls .v-col {
  padding: 0 4px;
}
#schedule_table th{
  text-align: left;
 width: calc(100% / 25);
}
#schedule_table{
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 15px;
}
#schedule_table tr {
}
#schedule_table td {
  margin-bottom: 2px !important;
  background-color: #3a78d1;
}
#schedule_table td:first-child {
  background-color: initial;
  width: 20px;
}
#schedule_table td:nth-child(2) {
  border-bottom-left-radius: 30px;
  border-top-left-radius: 30px;
}
#schedule_table td:last-child {
  border-bottom-right-radius: 30px;
  border-top-right-radius: 30px;
}
#schedule_table .heating{
  background-color: rgb(var(--v-theme-primary));
}
</style>
