<template>
  <ManualControl></ManualControl>
</template>

<script setup>
import ManualControl from '@/components/ManualControl.vue'
import { globalStore } from '@/main'
import { useLocale } from 'vuetify'

const { t } = useLocale()
globalStore.setAppbarHeading(t('manual.heading'))
</script>

<style scoped>

</style>
