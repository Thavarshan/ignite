<script setup lang="ts">
import ChannelCard from './partials/ChannelCard.vue';
import Default from '@/layouts/default.vue';
import { Channel } from '@/types/channel';
import { Paginated } from '@/types/paginate';

defineProps<{
    channels: Paginated<Channel>;
}>();
</script>

<template>
    <Default title="Channels">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <ChannelCard v-for="channel in channels.data" :key="channel.id" :channel="channel" />
        </div>
    </Default>
</template>
