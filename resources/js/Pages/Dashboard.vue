<script setup>
import FlowbiteLayout from '@/Layouts/FlowbiteLayout.vue';
import Welcome from '@/Components/Jetstream/Welcome.vue';
</script>

<template>
    <FlowbiteLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-md text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </FlowbiteLayout>
</template>
