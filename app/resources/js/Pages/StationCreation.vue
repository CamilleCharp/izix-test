<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Form } from '@primevue/forms';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Button from 'primevue/button';

const props = defineProps({station_types: Object, locations: Object})



const form = ref({
  name: '',
  spot: null,
  type_id: null,
  location_uuid: null, 
})

const onFormSubmit = () => {
  if(form.value.spot === null || form.value.spot < 0) {
    alert("You must provide a valid parking spot number");

    return;
  }

  if(form.value.name === null) {
    alert("You must provide a station name");

    return;
  }

  if(form.value.type_id === null) {
    alert("You must select a station type");

    return;
  }

  if (form.value.location_uuid === null) {
    alert('You must select where the station is located.');

    return;
  }

  router.post(route('stations_store'), {
    name: form.value.name,
    spot: form.value.spot,
    type_id: form.value.type_id,
    location_uuid: form.value.location_uuid 
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="h-full flex flex-col space-y-8 justify-between">
      
      <Form v-slot="$form" @submit="onFormSubmit" class="m-auto">
        <h1 class="text-xl">Register a new vehicle</h1>
        <div class="my-8">
          <FloatLabel>
            <InputText name="name" required v-model="form.name" />
            <label for="name">Station name</label>
          </FloatLabel>
        </div>

        <div class="my-8">
          <FloatLabel>
            <Select required editable name="type_id" v-model="form.type_id" :options="props.station_types" optionLabel="name" optionValue="id" class="w-full"/>
            <label for="type_id">Station type</label>
          </FloatLabel>
        </div>
        
        
        <div class="my-8">
          <FloatLabel>
            <Select required editable name="location_uuid" v-model="form.location_uuid" :options="props.locations" optionLabel="name" optionValue="uuid" class="w-full"/>
            <label for="location_uuid">Location</label>
          </FloatLabel>
        </div>

        <div class="my-8">
          <FloatLabel>
            <InputText name="spot" required v-model="form.spot" />
            <label for="spot">Parking spot number</label>
          </FloatLabel>
        </div>
        
        <Button type="submit">Register your vehicle</Button>
      </Form>
    </div>
  </AuthenticatedLayout>
</template>
