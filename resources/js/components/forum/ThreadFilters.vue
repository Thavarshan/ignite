<script setup lang="ts">
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { Channel } from '@/types';
import { router } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<{
    channels?: Channel[];
    filters?: {
        channel?: string;
        sort_by?: string;
        q?: string;
    };
    routeUrl: string;
    showChannelFilter?: boolean;
}>();

const search = ref(props.filters?.q ?? '');
const sortBy = ref(props.filters?.sort_by ?? 'latest');
const channel = ref(props.filters?.channel ?? 'all');

let searchTimeout: ReturnType<typeof setTimeout>;

function applyFilters() {
    const query: Record<string, string> = {};

    if (search.value) {
        query.q = search.value;
    }

    if (sortBy.value && sortBy.value !== 'latest') {
        query.sort_by = sortBy.value;
    }

    if (channel.value && channel.value !== 'all') {
        query.channel = channel.value;
    }

    router.get(props.routeUrl, query, {
        preserveState: true,
        preserveScroll: true,
    });
}

watch(sortBy, applyFilters);
watch(channel, applyFilters);

function onSearchInput() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 400);
}
</script>

<template>
    <div
        class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
    >
        <div class="flex flex-wrap items-center gap-2">
            <Select
                v-if="showChannelFilter !== false && channels?.length"
                v-model="channel"
            >
                <SelectTrigger class="h-8 w-40 text-xs">
                    <SelectValue placeholder="All channels" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All channels</SelectItem>
                    <SelectItem
                        v-for="ch in channels"
                        :key="ch.id"
                        :value="ch.slug"
                    >
                        {{ ch.name }}
                    </SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="sortBy">
                <SelectTrigger class="h-8 w-32.5 text-xs">
                    <SelectValue placeholder="Sort by" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="latest">Latest</SelectItem>
                    <SelectItem value="popular">Popular</SelectItem>
                    <SelectItem value="unanswered">Unanswered</SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div class="relative">
            <Search
                class="absolute top-1/2 left-2.5 size-3.5 -translate-y-1/2 text-muted-foreground"
            />
            <Input
                v-model="search"
                placeholder="Search threads..."
                class="h-8 w-full pl-8 text-xs sm:w-55"
                @input="onSearchInput"
            />
        </div>
    </div>
</template>
