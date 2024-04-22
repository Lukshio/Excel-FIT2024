<template>
  <v-container>
    <v-form @submit.prevent="$emit('on-submit', form)">
      <v-row class="justify-center align-center">
        <v-col cols="12" md="3" xl="3">
          <v-text-field
            v-model="form.name"
            hide-details
            disabled
            label="Name">
          </v-text-field>
        </v-col>
        <v-col cols="12" md="2" xl="2">
          <v-text-field
            v-model="form.value"
            hide-details
            label="Hodnota">
          </v-text-field>
        </v-col>
        <v-col cols="12" md="4" xl="4">
          <v-text-field
            v-model="form.description"
            hide-details
            label="Popis">
          </v-text-field>
        </v-col>
        <v-col cols="12" md="3" xl="3">
          <v-btn type="submit" block @submit="form">Uložit</v-btn>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
export default {
  name: 'SettingsForm',
  props: {
    rowData: {
      type: Object
    }
  },
  data () {
    return {
      form: {
        id: this.rowData.id,
        name: this.rowData?.name || '',
        value: this.rowData?.value || '',
        description: this.rowData?.description || ''
      }
    }
  },
  methods: {
    loadSettings () {
      this.$http.get('/settings')
        .then(response => {
          this.settings = response.data
        })
    }
  },
  created () {
    this.loadSettings()
  }
}
</script>

<style scoped>

</style>
