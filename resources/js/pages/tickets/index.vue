<script setup lang="ts">
import App from '@/layouts/app.vue';
import { Button } from '@/components/ui/button';
</script>

<template>
    <App title="Dashboard">
        <template #header> Dashboard </template>
        <div class="flex flex-1 items-center justify-center rounded-lg border shadow-sm py-10">
            <div class="flex flex-col items-center gap-1 text-center">
                <h3 class="text-2xl font-bold tracking-tight">You have no tickets</h3>
                <p class="text-sm text-muted-foreground">You can start engaging as soon as you add a ticket.</p>
                <Button class="mt-4"> Add Ticket </Button>
            </div>
        </div>
    </App>
</template>
