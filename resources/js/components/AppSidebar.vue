<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as channelsIndex } from '@/routes/channels';
import { index as threadsIndex } from '@/routes/threads';
import type { NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    Flag,
    FolderGit2,
    Hash,
    LayoutGrid,
    MessageSquare,
} from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const auth = computed(
    () => page.props.auth as { user?: { is_admin?: boolean } } | undefined,
);

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Threads',
        href: threadsIndex(),
        icon: MessageSquare,
    },
    {
        title: 'Channels',
        href: channelsIndex(),
        icon: Hash,
    },
];

const adminNavItems: NavItem[] = [
    {
        title: 'Flagged Content',
        href: '/admin/flags',
        icon: Flag,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
            <NavMain
                v-if="auth?.user?.is_admin"
                :items="adminNavItems"
                label="Admin"
            />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
