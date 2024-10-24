<script setup lang="ts" generic="T">
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/components/ui/pagination';

import type { Paginated, PaginationLink } from '@/types/paginate';

const props = defineProps<{
    pagination?: Paginated<T>;
}>();

const emit = defineEmits<{
    (e: 'change', page: number): void;
}>();

const currentPage = ref(props.pagination?.meta.current_page || 1);

function handleChange(page: number) {
    currentPage.value = page;
    emit('change', page);
}

const items = computed<PaginationLink[]>(() => {
    return (
        props.pagination?.meta.links.map((link: PaginationLink, index: number) => ({
            url: link.url,
            label: link.label,
            type: link.url ? 'page' : 'ellipsis',
            value: link.label,
            active: link.active || false,
            index,
        })) || []
    );
});
</script>

<template>
    <!-- eslint-disable vue/no-unused-vars -->
    <Pagination
        v-if="props.pagination"
        v-slot="{ page }"
        :total="props.pagination.meta.total"
        :items-per-page="props.pagination.meta.per_page"
        :sibling-count="1"
        show-edges
        :default-page="1"
        @update:page="handleChange"
        :page="currentPage"
        :key="currentPage"
    >
        <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <PaginationFirst @click="handleChange(1)" />
            <PaginationPrev @click="handleChange(currentPage - 1)" />

            <template v-for="(item, index) in items" :key="index">
                <PaginationListItem v-if="item.type === 'page'" :value="item.value" as-child>
                    <Button
                        class="w-10 h-10 p-0"
                        :variant="item.value === page ? 'default' : 'outline'"
                        @click="handleChange(item.value)"
                    >
                        {{ item.value }}
                    </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else :index="index" />
            </template>

            <PaginationNext @click="handleChange(currentPage + 1)" />
            <PaginationLast @click="handleChange(props.pagination.meta.last_page)" />
        </PaginationList>
    </Pagination>
</template>
