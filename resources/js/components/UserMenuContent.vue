<script setup lang="ts">
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import UserInfo from '@/components/UserInfo.vue';
import { dashboard, logout } from '@/routes';
import { edit } from '@/routes/profile';
import type { User } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { LayoutGrid, LogOut, Settings } from 'lucide-vue-next';
import { computed } from 'vue';

type Props = {
    user: User;
};

const handleLogout = () => {
    router.flushAll();
};

const page = usePage();
const isAdmin = computed(
    () =>
        !!(page.props.auth as { user?: { is_admin?: boolean } })?.user
            ?.is_admin,
);

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem v-if="isAdmin" :as-child="true">
            <Link class="block w-full cursor-pointer" :href="dashboard()">
                <LayoutGrid class="mr-2 h-4 w-4" />
                Dashboard
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full cursor-pointer" :href="edit()" prefetch>
                <Settings class="mr-2 h-4 w-4" />
                Settings
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Link
            class="block w-full cursor-pointer"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <LogOut class="mr-2 h-4 w-4" />
            Log out
        </Link>
    </DropdownMenuItem>
</template>
