<script setup lang="ts">
import App from '@/layouts/app.vue';
import { Button } from '@/components/ui/button';
</script>

<template>
    <App title="Dashboard">
        <template #header> Dashboard </template>

        <div class="mt-6 grid w-full max-w-6xl items-start gap-6">
            <Button>Click me</Button>
        </div>
    </App>
</template>
