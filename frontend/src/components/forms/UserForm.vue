<script setup>
import { onMounted, ref } from 'vue'
import LoadingComponent from '@/components/LoadingComponent.vue'
import { rules } from '@/composable/formRules'
import emitter from '@/plugins/eventBus'

const props = defineProps({
  userData: {
    type: Object
  }
})

const form = ref({
  id: props.userData?.id || null,
  name: props.userData?.name || null,
  email: props.userData?.email || null,
  password: props.userData?.password || null,
  role: props.userData?.role || null
})

const finishedLoading = ref(false)
const emit = defineEmits(['on-submit'])

function validateForm () {
  if (
    form.value.name === null || form.value.name === '' ||
    form.value.email === null || form.value.email === ''
  ) emitter.emit('general-error', 'Ve formuláři chybí data')
  else emit('on-submit', form.value)
}

onMounted(() => {
  finishedLoading.value = true
})

</script>

<template>
  <v-form @submit.prevent @submit="validateForm">
    <v-container>
      <v-row v-if="finishedLoading">
        <v-col cols="12" md="6" xl="4">
          <v-text-field
            variant="solo-filled"
            v-model="form.name"
            :label="$t('users.name')"
            hide-details
            :rules="[rules.required]"
          >
          </v-text-field>
        </v-col>

        <v-col cols="12" sm="6" md="6" xl="4">
          <v-text-field
            variant="solo-filled"
            v-model="form.email"
            :label="$t('users.email')"
            type="email"
            hide-details
            :rules="[rules.required]"
          >
          </v-text-field>
        </v-col>
        <v-col cols="12" sm="6" md="6" xl="4">
          <v-text-field
            variant="solo-filled"
            v-model="form.password"
            :label="$t('users.form.password')"
            type="password"
            hide-details
          >
          </v-text-field>
        </v-col>
        <v-col cols="12" sm="6" md="6">
          <v-select
            variant="solo-filled"
            :items="['admin', 'user']"
            v-model="form.role"
            hide-details
            :label="$t('users.role')"
          >
          </v-select>
        </v-col>
        <v-col cols="12">
          <v-btn
            type="submit"
            @submit="form"
            color="primary"
          >
            {{ $t('globals.saveBtn') }}
          </v-btn>
        </v-col>
      </v-row>
      <LoadingComponent v-else></LoadingComponent>
    </v-container>
  </v-form>
</template>

<style scoped>

</style>
