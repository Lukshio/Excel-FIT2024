<script setup>
import LoadingComponent from '@/components/LoadingComponent.vue'
import { onMounted, ref } from 'vue'
import { deviceStore, userStore, zoneStore } from '@/main'
import emitter from '@/plugins/eventBus'
import { rules } from '@/composable/formRules'

const props = defineProps({
  zoneData: {
    type: Object
  }
})

const form = ref({
  id: props.zoneData?.id || null,
  name: props.zoneData?.name || null,
  temperature: props.zoneData?.temperature || 21,
  mainSensorUuid: props.zoneData?.mainSensorUuid || null,
  relayDeviceId: props.zoneData?.relay || null,
  description: props.zoneData?.description || null,
  zoneTypeId: props.zoneData?.zoneTypeId || 1,
  autoHeating: !!props.zoneData?.autoHeating || null,
  kpiTreshold: props.zoneData?.kpiTreshold || 21,
  userId: props.zoneData?.userId || null,
  useProgram: !!props.zoneData?.useProgram || null
})

const finishedLoading = ref(false)
const emit = defineEmits(['on-submit'])
function validateForm () {
  if (
    form.value.name === null || form.value.name === ''
  ) emitter.emit('general-error', 'Ve formuláři chybí data')
  else emit('on-submit', form.value)
}

onMounted(() => {
  finishedLoading.value = true
})
</script>

<template>
  <v-form @submit.prevent @submit="validateForm">
    <h1 class="text-center" style="font-size: 1.2em"> {{form.name}}</h1>
    <v-container>
      <span class="my-3" style="color:#909090; font-size: 0.8em">{{ $t('globals.requiredForm' )}}</span>
      <v-row v-if="finishedLoading">
        <v-col cols="12" md="6">
          <v-text-field
            variant="solo-filled"
            v-model="form.name"
            :label="$t('zones.form.name')"
            :rules="[rules.required]"
            :disabled="userStore.userData.role !== 'admin'"
          >
          </v-text-field>
        </v-col>
        <v-col cols="12" sm="6" md="6">
          <label for="temp">{{ $t('zones.form.temp') }}</label>
          <v-slider
            type="number"
            v-model="form.temperature"
            id="temp"
            color="primary"
            thumb-label
            :step="0.5"
            min="-5"
            max="75"
          >
            <template v-slot:prepend>
              <v-btn
                size="small"
                variant="text"
                icon="mdi-minus"
                density="compact"
                color="primary"
                @click="form.temperature -= 0.5"
              ></v-btn>
            </template>
            <template v-slot:append>
              <v-btn
                size="small"
                variant="text"
                icon="mdi-plus"
                color="primary"
                density="compact"
                @click="form.temperature += 0.5"
              ></v-btn>
              <v-text-field
                v-model="form.temperature"
                hide-details
                single-line
                density="compact"
                style="width: 80px"
                type="number"
              ></v-text-field>
            </template>
          </v-slider>
        </v-col>
        <v-col cols="12" sm="6" md="6">
          <v-select
            variant="solo-filled"
            hide-selected
            :items="zoneStore.storeZoneTypes"
            :loading="!zoneStore.storeZoneTypes.length"
            :disabled="!zoneStore.storeZoneTypes.length || userStore.userData.role !== 'admin'"
            item-value="id"
            item-title="description"
            :label="$t('zones.form.zoneType')"
            :rules="[rules.required]"
            v-model="form.zoneTypeId">
          </v-select>
        </v-col>
        <v-col cols="12" sm="6" md="6">
          <v-select
            variant="solo-filled"
            :items="deviceStore.storeSensors"
            v-model="form.mainSensorUuid"
            item-value="uuid"
            item-title="name"
            :loading="!deviceStore.storeSensors.length && deviceStore.storeSensors.length !== 0"
            :disabled="!deviceStore.storeSensors.length || userStore.userData.role !== 'admin'"
            :label="$t('zones.form.mainSensor')"

          >
          </v-select>
        </v-col>
        <v-col cols="12" sm="6" md="6">
          <v-select
            variant="solo-filled"
            :items="deviceStore.storeFreeRelays"
            v-model="form.relayDeviceId"
            item-value="id"
            item-title="name"
            :loading="!deviceStore.storeFreeRelays.length && deviceStore.storeFreeRelays.length !== 0"
            :disabled="!deviceStore.storeFreeRelays.length || userStore.userData.role !== 'admin'"
            :label="$t('zones.form.relay')"
          >
          </v-select>
        </v-col>
        <v-col cols="12" sm="6" md="6">
          <label for="kpi">{{ $t('zones.form.kpi') }}</label>
          <v-slider
            type="number"
            v-model="form.kpiTreshold"
            id="kpi"
            color="primary"
            thumb-label
            :step="0.5"
            min="0"
            max="75"
            :disabled="userStore.userData.role !== 'admin'"

          >
            <template v-slot:prepend>
              <v-btn
                size="small"
                variant="text"
                icon="mdi-minus"
                color="primary"
                density="compact"
                @click="form.kpiTreshold -= 0.5"
              ></v-btn>
            </template>
            <template v-slot:append>
              <v-btn
                size="small"
                variant="text"
                density="compact"
                icon="mdi-plus"
                color="primary"
                @click="form.kpiTreshold += 0.5"
              ></v-btn>
              <v-text-field
                v-model="form.kpiTreshold"
                hide-details
                single-line
                density="compact"
                style="width: 80px"
                type="number"
              ></v-text-field>
            </template>
          </v-slider>
        </v-col>
        <v-col cols="12" sm="6" md="6">
          <v-select
            variant="solo-filled"
            :items="userStore.storeUsers"
            item-value="id"
            item-title="name"
            v-model="form.userId"
            :loading="!userStore.storeUsers.length"
            :disabled="!userStore.storeUsers.length || userStore.userData.role !== 'admin'"
            :label="$t('zones.form.user')"
          >
          </v-select>
        </v-col>
        <v-col cols="12" sm="6" md="6">
          <v-switch
            v-model="form.autoHeating"
            :label="$t('zones.form.auto')"
            color="primary"
            hide-details
          >
          </v-switch>
          <v-switch
            v-model="form.useProgram"
            :label="$t('zones.form.program')"
            color="primary"
            hide-details
          >
          </v-switch>
        </v-col>
        <v-col sm="6" md="6">
          <v-btn
            type="submit"
            color="primary"
            block
            variant="tonal"
            @submit="form"
          >{{ $t('globals.confirmBtn') }}</v-btn>
        </v-col>
      </v-row>
      <LoadingComponent v-else></LoadingComponent>
    </v-container>
  </v-form>
</template>

<style scoped>
label {
  color: #4a4a4a;
}
</style>
