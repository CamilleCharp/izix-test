<script setup>
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';

const props = defineProps({ vehicles: Object });
const confirm = useConfirm();

const confirmDelete = (vehicleUuid, plate) => {
  confirm.require({
    message: "Are you sure you want to delete vehicle with license plate " + plate.toUpperCase() + " ?",
    acceptLabel: "Confirm",
    rejectLabel: "Cancel",
    header: 'Delete Confirmation',
    accept: () => router.delete(route('vehicles_delete', vehicleUuid))
  })
}

const onPage = (event) => {
  const newPage = event.page + 1;

  router.get(route('vehicles_index'), { page: newPage }, { preserveState: true });
};
</script>

<template>
  <AuthenticatedLayout>
    
    <div class="w-full flex">
      <div class="w-max my-16 mx-auto space-y-8 flex flex-col">
        <h1 class="text-2xl">Your vehicles</h1>
        <DataTable
          :value="props.vehicles.data"
          :paginator="true"
          :lazy="true"
          :rows="props.vehicles.per_page"
          :totalRecords="props.vehicles.total"
          :first="(props.vehicles.current_page - 1) * props.vehicles.per_page"
          @page="onPage"
          class="mx-auto"
        >
          <Column  header="License plate">
              <template #body="slotProps">
                  <Button variant="link">
                      <Link :href="route('vehicles_show', slotProps.data.uuid)">
                          {{ slotProps.data.license_plate.toUpperCase() }}
                      </Link>
                  </Button>
              </template>
          </Column>
          <Column field="owner.name" header="Owner"></Column>
          <Column field="type.name" header="Model"></Column>
          <Column header="Battery capacity">
            <template #body="slotProps">
              {{ slotProps.data.type.battery_capacity / 1000 }}kWh
            </template>
          </Column>
          <Column header="Maximum power input from AC sources ">
            <template #body="slotProps">
              {{ slotProps.data.type.maximum_ac_input / 1000 }}kW
            </template>
          </Column>
          <Column field="type.maximum_dc_input" header="Maximum power input from DC sources">
            <template #body="slotProps">
              {{ slotProps.data.type.maximum_dc_input / 1000 }}kW
            </template>
          </Column>
          <Column header="Edit connector">
              <template #body="slotProps">
                  <Button variant="text">
                      <Link :href="route('vehicles_edit', slotProps.data.uuid)">
                          Edit
                      </Link>
                  </Button>
              </template>
          </Column>
          <Column header="Delete connector">
              <template #body="slotProps">
                  <Button severity="danger" variant="text" @click="confirmDelete(slotProps.data.uuid, slotProps.data.license_plate)">Delete</Button>
              </template>
          </Column>
        </DataTable>
  
        <Button class="max-w-64 mx-auto my-16">
              <Link :href="route('vehicles_create')">
                  Register a new vehicle   
              </Link>
        </Button>
      </div>
    </div>

    <ConfirmDialog></ConfirmDialog>
  </AuthenticatedLayout>
</template>