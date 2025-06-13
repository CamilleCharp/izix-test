<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Form } from '@primevue/forms';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Button from 'primevue/button';

const props = defineProps({connector: Object, connector_types: Object, stations: Object})

const initialValues = {
  type_id: props.connector.type.id,
  station_uuid: props.connector.station.uuid
}

const form = ref({
  type_id: props.connector.type.id,
  station_uuid: props.connector.station.uuid
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

  router.put(route('connectors_update', props.connector.uuid), {
    type_id: form.value.type_id,
    station_uuid: form.value.station_uuid
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="h-full flex flex-col justify-between">
      
      <Form v-slot="$form" @submit="onFormSubmit" :initialValues="initialValues" class="m-auto">
        <h1 class="text-xl">Change a connector infos</h1>
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
        
        
        <Button type="submit">Modify the connector infos</Button>
      </Form>
    </div>
  </AuthenticatedLayout>
</template>
