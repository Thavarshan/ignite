<script setup lang="ts">
import App from '@/layouts/app.vue';
import { Link } from '@inertiajs/vue3';
import UpdateProfileInformationForm from './partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from './partials/UpdatePasswordForm.vue';
import type { User } from '@/types/user';
import type { Session } from '@/types/session';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import TwoFactorAuthenticationForm from './partials/TwoFactorAuthenticationForm.vue';
import LogoutOtherBrowserSessionsForm from './partials/LogoutOtherBrowserSessionsForm.vue';

defineProps<{
    confirmsTwoFactorAuthentication: boolean;
    user: User;
    sessions: Session[];
}>();
</script>

<template>
    <App title="Settings">
        <template #header> Settings </template>

        <Breadcrumb>
            <BreadcrumbList>
                <BreadcrumbItem>
                    <BreadcrumbLink>
                        <Link :href="route('dashboard')"> Dashboard </Link>
                    </BreadcrumbLink>
                </BreadcrumbItem>
                <BreadcrumbSeparator />
                <BreadcrumbItem>
                    <BreadcrumbLink>
                        <Link :href="route('profile.show')"> Profile </Link>
                    </BreadcrumbLink>
                </BreadcrumbItem>
                <BreadcrumbSeparator />
                <BreadcrumbItem>
                    <BreadcrumbPage>Settings</BreadcrumbPage>
                </BreadcrumbItem>
            </BreadcrumbList>
        </Breadcrumb>

        <div class="mt-6 grid w-full max-w-6xl items-start gap-6">
            <UpdateProfileInformationForm :user="user" />

            <UpdatePasswordForm />

            <TwoFactorAuthenticationForm
                :user="user"
                :requires-confirmation="confirmsTwoFactorAuthentication"
                class="mt-10 sm:mt-0"
            />

            <LogoutOtherBrowserSessionsForm :sessions="sessions" />
        </div>
    </App>
</template>
