<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { show } from '@/routes/channels';
import type { Channel } from '@/types';

defineProps<{
    channel: Pick<Channel, 'id' | 'name' | 'slug'>;
}>();
</script>

<template>
    <Link :href="show.url(channel.slug)">
        <Badge
            variant="secondary"
            class="cursor-pointer font-normal transition-colors hover:bg-secondary/80"
        >
            {{ channel.name }}
        </Badge>
    </Link>
</template>
