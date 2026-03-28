<script setup lang="ts">
import RelativeTime from '@/components/forum/RelativeTime.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { CheckCircle, ExternalLink } from 'lucide-vue-next';

interface FlagData {
    id: number;
    reason: string;
    description: string | null;
    created_at: string;
    resolved_at: string | null;
    user?: { id: number; name: string };
    flaggable?: {
        id: number;
        type: string;
        title: string | null;
        body: string;
        slug: string | null;
    };
    resolver?: { id: number; name: string };
}

defineProps<{
    flags: { data: FlagData[]; meta: { current_page: number; last_page: number } };
    status: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '#' },
    { title: 'Flagged Content', href: '/admin/flags' },
];

function filterByStatus(status: string) {
    router.get('/admin/flags', { status }, { preserveState: true });
}

async function resolveFlag(flagId: number) {
    await axios.post(`/api/admin/flags/${flagId}/resolve`);
    router.reload({ only: ['flags'] });
}

function viewFlaggable(flag: FlagData) {
    if (flag.flaggable?.slug) {
        router.visit(`/threads/${flag.flaggable.slug}`);
    }
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Flagged Content</h1>
                <div class="flex gap-2">
                    <Button
                        :variant="status === 'pending' ? 'default' : 'outline'"
                        size="sm"
                        @click="filterByStatus('pending')"
                    >
                        Pending
                    </Button>
                    <Button
                        :variant="status === 'resolved' ? 'default' : 'outline'"
                        size="sm"
                        @click="filterByStatus('resolved')"
                    >
                        Resolved
                    </Button>
                    <Button
                        :variant="status === 'all' ? 'default' : 'outline'"
                        size="sm"
                        @click="filterByStatus('all')"
                    >
                        All
                    </Button>
                </div>
            </div>

            <div v-if="flags.data.length === 0" class="rounded-lg border border-border/60 px-4 py-12 text-center">
                <p class="text-muted-foreground">No flagged content found.</p>
            </div>

            <div v-else class="space-y-3">
                <div
                    v-for="flag in flags.data"
                    :key="flag.id"
                    class="rounded-lg border border-border/60 bg-card p-4"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1 space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <Badge variant="outline" class="text-xs">
                                    {{ flag.flaggable?.type ?? 'Unknown' }}
                                </Badge>
                                <Badge variant="destructive" class="text-xs" v-if="flag.reason === 'spam' || flag.reason === 'inappropriate'">
                                    {{ flag.reason }}
                                </Badge>
                                <Badge variant="secondary" class="text-xs" v-else>
                                    {{ flag.reason }}
                                </Badge>
                                <span v-if="flag.flaggable?.title" class="text-sm font-medium">
                                    {{ flag.flaggable.title }}
                                </span>
                            </div>
                            <p class="line-clamp-2 text-sm text-muted-foreground">
                                {{ flag.flaggable?.body }}
                            </p>
                            <p v-if="flag.description" class="text-sm italic text-muted-foreground">
                                "{{ flag.description }}"
                            </p>
                            <div class="flex items-center gap-3 text-xs text-muted-foreground">
                                <span>Reported by {{ flag.user?.name ?? 'Unknown' }}</span>
                                <RelativeTime :date="flag.created_at" />
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-1">
                            <Button
                                v-if="flag.flaggable?.slug"
                                variant="ghost"
                                size="sm"
                                @click="viewFlaggable(flag)"
                            >
                                <ExternalLink class="size-4" />
                            </Button>
                            <Button
                                v-if="!flag.resolved_at"
                                variant="outline"
                                size="sm"
                                @click="resolveFlag(flag.id)"
                            >
                                <CheckCircle class="mr-1.5 size-4" />
                                Resolve
                            </Button>
                            <span v-else class="text-xs text-muted-foreground">
                                Resolved{{ flag.resolver ? ` by ${flag.resolver.name}` : '' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
