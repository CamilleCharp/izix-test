<script setup>
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ connector: Object });

</script>

<template>
  <AuthenticatedLayout>
    <div class="w-full flex flex-col h-full">
      <div class="mx-auto my-16 flex flex-col space-y-6">
        <h1 class="text-xl w-max block">{{ connector.uuid }}</h1>

        <span>Type : {{ connector.type.name }}</span>
        <span>Station : {{ connector.station.name}}</span>
        <span>Current type : {{ connector.type.current_type }}</span>
        <span>Maximum Output : {{ connector.type.max_watts / 1000}}kW</span>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
