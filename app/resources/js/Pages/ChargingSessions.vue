<script setup>
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';

const props = defineProps({ charging_sessions: Object });

const calculateDuration = (startedAt, endedAt) => {
  const start = new Date(startedAt);
  const end = new Date(endedAt);
  const diffMs = end - start; // Difference in milliseconds

  const hours = Math.floor(diffMs / (1000 * 60 * 60));
  const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

  return `${hours}h ${minutes}m`;
};

const onPage = (event) => {
  const newPage = event.page + 1;

  router.get(route('charging-sessions_index'), { page: newPage }, { preserveState: true });
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="w-full flex">
      <div class="w-max my-16 mx-auto space-y-8 flex flex-col">
        <h1 class="text-2xl">Your vehicles</h1>
        <DataTable
          :value="props.charging_sessions.data"
          :paginator="true"
          :lazy="true"
          :rows="props.charging_sessions.per_page"
          :totalRecords="props.charging_sessions.total"
          :first="(props.charging_sessions.current_page - 1) * props.charging_sessions.per_page"
          @page="onPage"
          class="mx-auto"
        >
            <Column header="Started at">
                <template #body="slotProps">
                    {{ new Date(slotProps.data.started_at).toLocaleDateString() + ' ' + new Date(slotProps.data.started_at).toLocaleTimeString() }}
                </template>
            </Column>
            <Column field="vehicle" header="Vehicle"></Column>
            <Column field="license_plate" header="License plate"></Column>
            <Column header="Started at">
                <template #body="slotProps">
                    {{ slotProps.data.starting_battery_percent }}%
                </template>
            </Column>
            <Column header="At">
                <template #body="slotProps">
                    {{ slotProps.data.current_battery_percent }}%
                </template>
            </Column>
            <Column header="Ended at at">
                <template #body="slotProps">
                    {{ slotProps.data.ended_at === null ? "Still running" :  new Date(slotProps.data.ended_at).toLocaleDateString() + ' ' + new Date(slotProps.data.ended_at).toLocaleTimeString() }}
                </template>
            </Column>

            <Column header="Running for">
                <template #body="slotProps">
                    {{ slotProps.data.ended_at === null 
                        ? "Still running" 
                        : calculateDuration(slotProps.data.started_at, slotProps.data.ended_at) }}
                </template>
            </Column>
        </DataTable>
  
        <Button class="max-w-64 mx-auto my-16">
              <Link :href="route('charging-sessions_prepare')">
                  Start a new simulation   
              </Link>
        </Button>
      </div>
    </div>

    <ConfirmDialog></ConfirmDialog>
  </AuthenticatedLayout>
</template>