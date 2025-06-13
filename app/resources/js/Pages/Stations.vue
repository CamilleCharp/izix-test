<script setup>
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';

const props = defineProps({ stations: Object });
const confirm = useConfirm();

const confirmDelete = (stationUuid, name) => {
  confirm.require({
    message: "Are you sure you want to delete station " + name + " ?",
    acceptLabel: "Confirm",
    rejectLabel: "Cancel",
    header: 'Delete Confirmation',
    accept: () => router.delete(route('stations_delete', stationUuid))
  })
}

const onPage = (event) => {
  const newPage = event.page + 1;

  router.get(route('stations_index'), { page: newPage }, { preserveState: true });
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="w-full flex">
      <div class="w-max my-16 mx-auto space-y-8 flex flex-col">
        <h1 class="text-2xl">Stations</h1>
        <DataTable
          :value="props.stations.data"
          :paginator="true"
          :lazy="true"
          :rows="props.stations.per_page"
          :totalRecords="props.stations.total"
          :first="(props.stations.current_page - 1) * props.stations.per_page"
          @page="onPage"
          class="mx-auto"
        >
          <Column field="name" header="Name"></Column>
          <Column field="spot" header="Parking spot"></Column>
          <Column field="type.name" header="Type"></Column>
          <Column field="location.name" header="Located at"></Column>
          <Column header="Max power output">
            <template #body="slotProps">
              {{ slotProps.data.type.power / 1000 }}kW
            </template>
          </Column>
          <Column header="Edit station">
              <template #body="slotProps">
                  <Button variant="text">
                      <Link :href="route('stations_edit', slotProps.data.uuid)">
                          Edit
                      </Link>
                  </Button>
              </template>
          </Column>
          <Column header="Delete station">
              <template #body="slotProps">
                  <Button severity="danger" variant="text" @click="confirmDelete(slotProps.data.uuid, slotProps.data.name)">Delete</Button>
              </template>
          </Column>
        </DataTable>
  
        <Button class="max-w-64 mx-auto my-16">
              <Link :href="route('stations_create')">
                  Register a new station   
              </Link>
        </Button>
      </div>
    </div>

    <ConfirmDialog></ConfirmDialog>
  </AuthenticatedLayout>
</template>