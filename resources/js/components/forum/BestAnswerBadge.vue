<script setup lang="ts">
import { CheckCircle } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
</script>

<template>
    <Badge
        variant="secondary"
        class="border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950 dark:text-green-400"
    >
        <CheckCircle class="mr-1 size-3" />
        Best Answer
    </Badge>
</template>
