<script setup>
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ station: Object });

</script>

<template>
  <AuthenticatedLayout>
    <div class="w-full flex flex-col h-full">
      <div class="mx-auto my-16 flex flex-col space-y-6">
        <h1 class="text-xl w-max block">{{ station.name }}</h1>

        <span>Type : {{ station.type.name }}</span>
        <span>Location : {{ station.location.name }} (Spot {{  station.spot }})</span>
        <span>Maximum Output : {{ station.type.power / 1000}}kW</span>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
