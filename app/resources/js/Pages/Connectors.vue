<script setup>
import { computed } from 'vue'
import { router, usePage,Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';

const props = defineProps({ connectors: Object });

const confirm = useConfirm();

const confirmDelete = (connectorUuid, address) => {
  confirm.require({
    message: "Are you sure you want to delete the connector located at " + address + " ?",
    acceptLabel: "Confirm",
    rejectLabel: "Cancel",
    header: 'Delete Confirmation',
    accept: () => router.delete(route('connectors_delete', connectorUuid))
  })
}

const page = usePage()
const user = computed(() => page.props.auth.user);
const roles = computed(() => page.props.auth.roles);

const onPage = (event) => {
  const newPage = event.page + 1;
  const perPage = event.rows;
  router.get(route('connectors_index'), { page: newPage }, { preserveState: true });
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-full flex flex-col my-16">
            <h1 class="text-2xl">Connectors</h1>
            <DataTable
                :value="props.connectors.data"
                :paginator="true"
                :lazy="true"
                :rows="props.connectors.per_page"
                :totalRecords="props.connectors.total"
                :first="(props.connectors.current_page - 1) * props.connectors.per_page"
                @page="onPage"
                class="mx-auto"
            >
                <Column field="uuid" header="Connector uuid">
                    <template #body="slotProps">
                        <Button variant="link">
                            <Link :href="route('connectors_show', slotProps.data.uuid)">
                                {{ slotProps.data.uuid }}
                            </Link>
                        </Button>
                    </template>
                </Column>
                <Column field="connector_type.name" header="Connector type"></Column>
                <Column field="location.name" header="Located at"></Column>
                <Column field="station.name" header="Station"></Column>
                <Column header="Maximum power output">
                    <template #body="slotProps">
                        {{ slotProps.data.connector_type.max_watts / 1000 }}kW
                    </template>
                </Column>
                <Column field="connector_type.current_type" header="Current type"></Column>
                <Column header="Edit connector">
                    <template #body="slotProps">
                        <Button variant="text">
                            <Link :href="route('connectors_edit', slotProps.data.uuid)">
                                Edit
                            </Link>
                        </Button>
                    </template>
                </Column>
                <Column header="Delete connector">
                    <template #body="slotProps">
                        <Button severity="danger" variant="text" @click="confirmDelete(slotProps.data.uuid, slotProps.data.location.name)">Delete</Button>
                    </template>
                </Column>
            </DataTable>

            <Button class="max-w-64 mx-auto my-16">
                <Link :href="route('connectors_create')">
                    Register a new connector   
                </Link>
          </Button>
        </div>


        <ConfirmDialog></ConfirmDialog>
    </AuthenticatedLayout>
</template>