<script setup>

import { globalStore, userStore } from '@/main'
import { computed, ref } from 'vue'
import { useTheme } from 'vuetify'

const rail = ref(true)
const full = ref(computed(() => window.innerWidth > 900))

const theme = useTheme()

function changeTheme () {
  theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
  globalStore.changeTheme()
}
</script>

<template>
  <v-card
    color="navigation"
    v-if="userStore.isLogged"
  >
    <nav>
      <v-navigation-drawer
        :rail="full ? false : rail"
        permanent
        floating
        app
      >
        <v-list
          nav
          density="compact"
        >
          <div
            v-if="!full"
            id="railnav_arrow"
          >
            <v-btn
              variant="text"
              icon="mdi-chevron-left"
              v-if="!rail"
              @click.stop="rail = !rail"
              density="compact"
            ></v-btn>
            <v-btn
              v-else
              density="compact"
              variant="text"
              icon="mdi-chevron-right"
              @click.stop="rail = !rail"
            ></v-btn>
          </div>
          <v-list-item
            id="title"
            prepend-icon="mdi-fire"
            title="Heating"
            subtitle="Automatic"
            style="padding-left: 8px"
          >
          </v-list-item>
          <div id="navbar_items">
            <v-list-item link prepend-icon="mdi-home-analytics" :to="{ name: 'home'}" :title="$t('navDrawer.home')"></v-list-item>
            <v-list-item link prepend-icon="mdi-thermometer" :to="{ name: 'temperatures' }" :title="$t('navDrawer.temperatures')"></v-list-item>
            <v-list-item link prepend-icon="mdi-home-map-marker" :to="{ name: 'zoneIndex'}" :title="$t('navDrawer.zoneIndex')"></v-list-item>
            <v-list-item v-if="userStore.userData.role === 'admin'" link prepend-icon="mdi-access-point" :to="{ name: 'deviceIndex'}" :title="$t('navDrawer.deviceIndex')"></v-list-item>
            <v-list-item link prepend-icon="mdi-chart-bar" :to="{ name: 'scheduler'}" title="Program"></v-list-item>
            <v-list-item v-if="userStore.userData.role === 'admin'" link prepend-icon="mdi-toggle-switch" :to="{ name: 'manual'}" :title="$t('navDrawer.manual')"></v-list-item>
            <v-list-item link prepend-icon="mdi-chart-line" :to="{ name: 'graphs'}" title="Grafy"></v-list-item>
            <v-list-item v-if="userStore.userData.role === 'admin'" link prepend-icon="mdi-post-outline" :to="{ name: 'system-logs'}" :title="$t('navDrawer.systemLog')"></v-list-item>
            <v-list-item v-if="userStore.userData.role === 'admin'" link prepend-icon="mdi-post-outline" :to="{ name: 'temperature-logs'}" :title="$t('navDrawer.tempLog')"></v-list-item>
            <v-list-item v-if="userStore.userData.role === 'admin'" link prepend-icon="mdi-account-multiple" :to="{ name: 'usersIndex'}" :title="$t('navDrawer.users')"></v-list-item>
            <v-list-item v-if="userStore.userData.role === 'admin'" link prepend-icon="mdi-cogs" :to="{ name: 'settings' }" :title="$t('navDrawer.settings')"></v-list-item>
            <v-list-item><v-switch class="d-flex" v-model="globalStore.getDarkTheme" hide-details dense @click="changeTheme" color="tertiary" prepend-icon="mdi-weather-sunny" append-icon="mdi-weather-night"></v-switch></v-list-item>
          </div>
        </v-list>
        <template v-slot:append>
          <v-btn block @click="userStore.logout()" color="primary" variant="tonal">{{$t('navDrawer.logout')}}</v-btn>
        </template>
      </v-navigation-drawer>
    </nav>
  </v-card>
</template>

<style scoped>
#title {
  font-size: 1.5em;
  color: white;
  border-radius: 0;
  background-color: rgb(var(--v-theme-primaryBgGradFirst));
  background-image: linear-gradient(180deg, rgb(var(--v-theme-primaryNavbarBgGradFirst)) 0%, rgb(var(--v-theme-primaryNavbarBgGradSecond)) 100%);
  padding: 15px 0 15px 0;
}

#title :deep(.v-list-item-title) {
  font-size: 0.7em !important;
}
.v-list{
  padding: 0;
}
#navbar_items :deep(i){
  color: rgb(var(--v-theme-primary));
  padding-left: 8px;
}
#railnav_arrow {
  color: white;
  background-color: rgb(var(--v-theme-primaryNavbarBgGradFirst));
  margin: auto;
}
#railnav_arrow > button {
  display: block;
  margin: auto !important;
}
</style>
