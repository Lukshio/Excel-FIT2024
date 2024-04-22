<template>
  <v-app id="app" app>
    <v-main>
      <NavigationDrawer></NavigationDrawer>
      <AppBar></AppBar>
      <router-view />
      <AlertSnackbar></AlertSnackbar>
    </v-main>
  </v-app>
</template>

<script setup>
import { onBeforeMount } from 'vue'
import { useTheme } from 'vuetify'
const theme = useTheme()

onBeforeMount(() => {
  const data = JSON.parse(localStorage.getItem('uiData'))
  if (data !== null && data.darkTheme === true) theme.global.name.value = 'dark'
})
</script>

<script>

import { useI18n } from 'vue-i18n'
import { RouterView } from 'vue-router'
import NavigationDrawer from '@/views/layouts/NavigationDrawer.vue'
import AppBar from '@/views/layouts/AppBar.vue'
import AlertSnackbar from '@/views/layouts/AlertSnackbar.vue'

export default {
  name: 'App',
  components: { AlertSnackbar, AppBar, NavigationDrawer, RouterView },
  setup () {
    const { t } = useI18n()
    return { t }
  },
  mounted () {
    document.body.style.backgroundColor = this.backgroundColor
  }
}
</script>
<style scoped>
.active{
  color: rgb(var(--v-theme-tertiary));
}
.nav{
  margin: 5px 7px 5px 7px;
  font-family: 'Lexend', serif;
  font-weight: 700;
}
</style>
