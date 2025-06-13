<script setup>
import { computed, ref } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Form } from '@primevue/forms';
import FloatLabel from 'primevue/floatlabel';
import InputNumber from 'primevue/inputnumber';
import Select from 'primevue/select';
import Button from 'primevue/button';

const props = defineProps({ vehicles: Object, connectors: Object })
const page = usePage();

const simulator_url = computed(() => page.props.simulator_url);


const form = ref({
    starting_charge: 50,
    speed: 200,
    vehicle_uuid: null,
    connector_uuid: null,
});


const onFormSubmit = async () => {
    if(form.value.speed === null || form.value.speed < 1) {
        alert("Your simulation must at least run at normal speed")

        return;
    }

    if(form.value.starting_charge === null || form.value.starting_charge < 0 || form.value.starting_charge > 100) {
        alert("You must provide a valid battery percentage");

        return;
    }

    if(form.value.vehicle_uuid === null) {
        alert("You must provide the vehicle to simulate");

        return;
    }

    if(form.value.connector_uuid === null) {
        alert("You must provide the connector to use");

        return;
    }

    try {
        const response = await axios.post(simulator_url.value + '/charge/start', {
            connector_uuid: form.value.connector_uuid,
            vehicle_uuid: form.value.vehicle_uuid,
            speed: form.value.speed,
            starting_charge: form.value.starting_charge
        });

        if(response.status === 200) {
            router.get(route('charging-sessions_index'));
        } else {
            alert("An error occured during the simulation creation");
        }

    } catch (error) {
        console.error(error);
    }

}
</script>

<template>
    <AuthenticatedLayout>
        <div class="h-full flex flex-col justify-between">

            <Form v-slot="$form" @submit="onFormSubmit" class="m-auto">
                <h1 class="text-xl">Start a new simulation</h1>
                <div class="my-8">
                    <FloatLabel>
                        <Select required editable name="vehicle" v-model="form.vehicle_uuid" :options="props.vehicles"
                            optionLabel="license_plate" optionValue="uuid" class="w-full" />
                        <label for="plate">Vehicle to charge</label>
                    </FloatLabel>
                </div>

                <div class="my-8">
                    <FloatLabel>
                        <InputNumber name="starting_charge" v-model="form.starting_charge" inputId="integeronly" fluid />
                        <label for="starting_charge">Starting battery (%)</label>
                    </FloatLabel>
                </div>

                <div class="my-8">
                    <FloatLabel>
                        <Select fluid required editable name="connector" v-model="form.connector_uuid" :options="props.connectors" optionLabel="type.name" optionValue="uuid" class="w-full">
                            <template #option="slotProps">
                                <span>Station : {{ slotProps.option.station.name }} / Connector : {{ slotProps.option.type.name }}</span>
                            </template>
                        </Select>
                        <label for="connector">Connector</label>
                    </FloatLabel>
                </div>

                <div class="my-8">
                    <FloatLabel>
                        <InputNumber name="speed" v-model="form.speed" inputId="integeronly" fluid />
                        <label for="speed">Simulation speed</label>
                    </FloatLabel>
                </div>

                <Button type="submit">Start the simulation</Button>
            </Form>
        </div>
    </AuthenticatedLayout>
</template>
