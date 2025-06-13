<script setup>
import { ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Form } from '@primevue/forms';
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Button from 'primevue/button';

const props = defineProps({vehicle_types: Object, vehicle: Object})

const initialValues = {
  plate: props.vehicle.license_plate,
  type: props.vehicle.type.id
}

const form = ref({
  plate: props.vehicle.license_plate,
  type: props.vehicle.type.id
})

const onFormSubmit = () => {
  const platePattern = /^\d-[A-Z]{3}-\d{3}$/;

  if(!platePattern.test(form.value.plate)) {
    alert("License plate must look like this : 2-AAA-999")

    return;
  }

  if (form.value.type === null) {
    alert('You must select a vehicle type.');

    return;
  }

  router.put(route('vehicles_update', props.vehicle.uuid), {
    plate: form.value.plate,
    type: form.value.type
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="h-full flex flex-col justify-between">
      
      <Form v-slot="$form" @submit="onFormSubmit" :initialValues="initialValues" class="m-auto">
        <h1 class="text-xl">Register a new vehicle</h1>
        <div class="my-8">
          <FloatLabel>
            <InputText name="plate" required v-model="form.plate" />
            <label for="plate">Vehicle license plate</label>
          </FloatLabel>
        </div>
        
        <div class="my-8">
          <FloatLabel>
            <Select required editable name="type" v-model="form.type" :options="props.vehicle_types" optionLabel="name" optionValue="id" class="w-full"/>
            <label for="plate">Vehicle type</label>
          </FloatLabel>
        </div>
        
        <Button type="submit">Modify the vehicle infos</Button>
      </Form>
    </div>
  </AuthenticatedLayout>
</template>
