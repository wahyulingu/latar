<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'bg-blue-700 md:bg-transparent text-white md:text-blue-700 dark:text-white transition'
        : 'text-gray-700 hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent transition';
});
</script>

<template>
    <Link class="block py-2 pr-4 pl-3 rounded md:p-0" :href="href" :class="classes">
    <slot />
    </Link>
</template>
