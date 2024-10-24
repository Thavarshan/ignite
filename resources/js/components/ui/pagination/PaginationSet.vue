<script setup lang="ts" generic="T">
import { ref } from 'vue';
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

import type { Pagination as PaginationType } from '@/types/paginate';

const props = defineProps<{
    pagination?: PaginationType<T>;
}>();

const emit = defineEmits<{
    (e: 'change', page: number): void;
}>();

const currentPage = ref(props.pagination?.current_page || 1);

function handleChange(page: number) {
    currentPage.value = page;
    emit('change', page);
}
</script>

<template>
    <Pagination
        v-if="props.pagination"
        v-slot="{ page }"
        :total="props.pagination.total"
        :items-per-page="props.pagination.per_page"
        :sibling-count="1"
        show-edges
        :default-page="1"
        @update:page="handleChange"
        :page="currentPage"
        :key="currentPage"
    >
        <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <PaginationFirst />
            <PaginationPrev />

            <template v-for="(item, index) in items">
                <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                    <Button class="w-10 h-10 p-0" :variant="item.value === page ? 'default' : 'outline'">
                        {{ item.value }}
                    </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else :key="item.type" :index="index" />
            </template>

            <PaginationNext />
            <PaginationLast />
        </PaginationList>
    </Pagination>
</template>
