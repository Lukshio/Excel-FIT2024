<template>
  <v-container id="bg" fluid style="height: 100vh" class="text-center">
    <h1 class="mt-15 mb-4">Přihlášení</h1>
    <LoginForm class="ma-auto"></LoginForm>
  </v-container>
</template>

<script setup>
import LoginForm from '@/components/forms/LoginForm.vue'

</script>

<style scoped>
h1 {
  color: #1c2733;
}
:deep(.input input){
  background-color: #f4f4f4 !important;
}
#bg {
  background: #C02425;
  background: -webkit-linear-gradient(to right, #F0CB35, #C02425);
  background: linear-gradient(to right, #F0CB35, #C02425);
}
</style>

<style>
</style>
