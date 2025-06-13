<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Form } from '@primevue/forms';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Button from 'primevue/button';

const props = defineProps({connector_types: Object, stations: Object})

const form = ref({
  type_id: null,
  station_uuid: null
})

const onFormSubmit = () => {
  if(!form.value.type_id) {
    alert("You must specify the connector type");

    return
  }

  if(!form.value.station_uuid) {
    alert("The connector must be linked with a charging stations");

    return
  }

  router.post(route('connectors_store'), {
    type_id: form.value.type_id,
    station_uuid: form.value.station_uuid
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="h-full flex flex-col justify-between">
      
      <Form v-slot="$form" @submit="onFormSubmit" class="m-auto">
        <h1 class="text-xl">Create a new connector</h1>
                
        <div class="my-8">
          <FloatLabel>
            <Select required editable name="type_id" v-model="form.type_id" :options="props.connector_types" optionLabel="name" optionValue="id" class="w-full"/>
            <label for="type_id">Connector type</label>
          </FloatLabel>
        </div>

        <div class="my-8">
          <FloatLabel>
            <Select required editable name="station_uuid" v-model="form.station_uuid" :options="props.stations" optionLabel="name" optionValue="uuid" class="w-full"/>
            <label for="station_uuid">Station</label>
          </FloatLabel>
        </div>
        
        
        <Button type="submit">Create the new connector</Button>
      </Form>
    </div>
  </AuthenticatedLayout>
</template>
