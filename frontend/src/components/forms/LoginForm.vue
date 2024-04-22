<template>
  <v-card class="px-5 py-6 rounded-lg" id="bg-card" style="max-width: 450px">
    <v-form @keydown.enter="userStore.login(loginForm)">
      <v-text-field
        v-model="loginForm.name"
        placeholder="Login"
        variant="outlined"
        class="input mb-3"
        hide-details
        :rules="[rules.required]"
      ></v-text-field>
      <v-text-field
        v-model="loginForm.password"
        placeholder="Password" type="password"
        variant="outlined"
        class="input mb-3"
        hide-details
        :rules="[rules.required]"
      ></v-text-field>
      <v-btn @click="userStore.login(loginForm)"><b>Log in</b></v-btn>
    </v-form>
  </v-card>
</template>

<script setup>
import { userStore } from '@/main'
import { ref } from 'vue'
import { rules } from '@/composable/formRules'

const loginForm = ref({
  name: '',
  password: ''
})
</script>

<style scoped>
#bg-card {
  background: #005C97;  /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #363795, #005C97);  /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #363795, #005C97); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
}

:deep(input) {
  color: black;
}
</style>
