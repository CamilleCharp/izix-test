<script setup>
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

const props = defineProps({ vehicle: Object });

</script>

<template>
  <AuthenticatedLayout>
    <div class="w-full flex flex-col h-full">
      <div class="mx-auto my-16 flex flex-col space-y-6">
        <h1 class="text-xl w-max block">{{ vehicle.license_plate.toUpperCase() }}</h1>

        <span>Uuid : {{ vehicle.uuid }}</span>
        <span>Owner : {{ vehicle.owner.name}}</span>
        <span>Type : {{ vehicle.type.name}}</span>
        <span>Maximum AC Input : {{ vehicle.type.maximum_ac_input / 1000}}kW</span>
        <span>Maximum DC Input : {{ vehicle.type.maximum_dc_input / 1000}}kW</span>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
