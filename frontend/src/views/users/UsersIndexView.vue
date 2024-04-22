<template>
  <v-container>
    <v-btn class="mb-4" variant="tonal" color="primary" @click="createForm = true">
      {{ $t('users.newUser')}}
    </v-btn>
    <v-card>
      <v-card-title>
        {{ $t('users.users')}}
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          :label="$t('users.search')"
          hide-details>
        </v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="users"
        :search="search">
        <template v-slot:item.links="{ item }">
          <v-icon @click="() => {
              currentUser = item;
              editForm = true;
            }">mdi-pencil</v-icon>
          <v-icon @click="() => {
              deleteUserVar = item;
              deleteUser(item);
            }">mdi-delete</v-icon>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
  <v-dialog v-model="editForm" max-width="600px">
    <v-card>
      <v-card-title>{{ $t('users.editUser')}}</v-card-title>
      <UserForm
        :is-update="true"
        :user-data="currentUser"
        @on-submit="userStore.updateUser"
      ></UserForm>
    </v-card>
  </v-dialog>

  <v-dialog v-model="createForm" max-width="600px">
    <v-card>
      <v-card-title>{{ $t('users.newUser')}}</v-card-title>
      <UserForm @on-submit="userStore.insertUser"></UserForm>
    </v-card>
  </v-dialog>

  <v-dialog max-width="500" v-model="deleteDialog">
    <v-card class="px-10 delete_dialog">
      <v-card-text class="text-center font-weight-bold">
        {{ $t('users.deleteConfirm', deleteUserVar.name)}}
      </v-card-text>
      <v-card-actions class="justify-space-between">
        <v-btn color="red" variant="tonal" @click="deleteDialog = false">{{ $t('globals.cancelBtn')}}</v-btn>
        <v-btn variant="text" color="green" @click="confirmDelete()" :loading="deleteLoading">{{ $t('globals.confirmBtn')}}</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useUserStore } from '@/stores/userStore'
import UserForm from '@/components/forms/UserForm.vue'
import { useLocale } from 'vuetify'
const { t } = useLocale()

const search = ref('')
const userStore = useUserStore()
const headers = ref([{
  align: 'start',
  key: 'name',
  sortable: false,
  title: t('users.name')
},
{ key: 'email', title: t('users.email') },
{ key: 'role', title: t('users.role') },
{ key: 'links', title: t('users.links'), sortable: false }
])
const users = computed(() => userStore.storeUsers)
const createForm = ref(false)
const editForm = ref(false)
const currentUser = ref(null)
const deleteDialog = ref(false)
const deleteUserVar = ref(null)
const deleteLoading = ref(false)

const deleteUser = (user) => {
  deleteDialog.value = true
  deleteUserVar.value = user
}

const confirmDelete = () => {
  deleteLoading.value = true
  userStore.deleteUser(deleteUserVar.value)
  deleteLoading.value = false
  deleteDialog.value = false
}

</script>

<style scoped></style>
