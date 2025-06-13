<script setup>
import { computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import Button from "primevue/button"
import Card from "primevue/card"
import { Link } from '@inertiajs/vue3';

const page = usePage();

const user = computed(() => page.props.auth.user);
const admin = computed(() => page.props.auth.admin);


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Welcome, {{ user.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden flex flex-col justify-between bg-neutral-800 px-16 py-8 shadow-sm sm:rounded-lg"
                >

                <span class="text-2xl my-4">Access your informations</span>

                <div class="flex space-x-8 justify-center">
                    <Link :href="route('vehicles_index')">
                        <Card class="w-64 min-h-full transform duration-300 hover:w-72">
                            <template #header>
                                <img src="/assets/img/ev.jpg" alt="A pic of an electric vehicle">
                            </template>
                            <template #title>Vehicles</template>
                            <template #content>
                                <p>
                                    See your vehicles, modify it or register a new one
                                </p>
                            </template>
                        </Card>
                    </Link>

                    <Link :href="route('charging-sessions_index')">
                        <Card class="w-64 min-h-full transform duration-300 hover:w-72">
                            <template #header>
                                <img src="/assets/img/session.jpg" alt="A pic of an electric vehicle">
                            </template>
                            <template #title>Charging session</template>
                            <template #content>
                                <p>
                                    See all your pasts charges, and start new ones (Simulation only)
                                </p>
                            </template>
                        </Card>
                    </Link>
                </div>

                <span class="text-2xl my-4">Admin only informations</span>

                    <div class="flex space-x-8 justify-center">
                        <Link :href="route('connectors_index')">
                            <Card class="w-64 min-h-full transform duration-300 hover:w-72">
                                <template #header>
                                    <img src="/assets/img/connector.jpg" alt="A pic of an electric vehicle">
                                </template>
                                <template #title>Connectors</template>
                                <template #content>
                                    <p>
                                        See all the registered connectors
                                    </p>
                                </template>
                            </Card>
                        </Link>
                        
                        <div class="flex space-x-8 justify-center">
                            <Link :href="route('stations_index')">
                                <Card class="w-64 min-h-full transform duration-300 hover:w-72">
                                    <template #header>
                                        <img src="/assets/img/station.jpg" alt="A pic of an electric vehicle">
                                    </template>
                                    <template #title>Stations</template>
                                    <template #content>
                                        <p>
                                            See all the registered stations
                                        </p>
                                    </template>
                                </Card>
                            </Link>
                        </div>

                </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
