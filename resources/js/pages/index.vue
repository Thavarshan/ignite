<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const products = ref([
    { code: 'A1', name: 'Product 1', category: 'Category 1', quantity: 10 },
    { code: 'A2', name: 'Product 2', category: 'Category 1', quantity: 20 },
    { code: 'A3', name: 'Product 3', category: 'Category 2', quantity: 5 },
    { code: 'A4', name: 'Product 4', category: 'Category 2', quantity: 10 },
    { code: 'A5', name: 'Product 5', category: 'Category 3', quantity: 5 },
]);
</script>

<template>
    <Head title="Home" />

    <div class="container space-y-6 py-20">
        <Link href="/login" class="hover:underline">Login</Link>
    </div>
</template>
