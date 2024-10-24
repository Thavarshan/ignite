<script setup lang="ts">
import { useToast } from '@/components/ui/toast/use-toast';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input, InputError } from '@/components/ui/input';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Monitor, Smartphone } from 'lucide-vue-next';
import type { Session } from '@/types/session';

defineProps<{
    sessions: Session[];
}>();

const { toast } = useToast();
const confirmingLogout = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmLogout = () => {
    confirmingLogout.value = true;

    setTimeout(() => {
        if (passwordInput.value) {
            passwordInput.value.focus();
        }
    }, 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            toast({
                title: 'Password Updated!',
                description: 'Your password has been updated.',
            });
        },
        onError: () => {
            if (passwordInput.value) {
                passwordInput.value.focus();
            }

            toast({
                title: 'Oops!',
                description: 'There was an error updating your password.',
            });
        },
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;

    form.reset();
};
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Browser Sessions</CardTitle>
            <CardDescription> Manage and log out your active sessions on other browsers and devices. </CardDescription>
        </CardHeader>
        <CardContent>
            <div class="max-w-xl">
                <p class="text-sm text-muted-foreground">
                    If necessary, you may log out of all of your other browser sessions across all of your devices. Some
                    of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your
                    account has been compromised, you should also update your password.
                </p>
            </div>

            <div v-if="sessions.length > 0" class="mt-5 space-y-6">
                <div v-for="(session, i) in sessions" :key="i" class="flex items-center">
                    <div>
                        <Monitor v-if="session.agent.is_desktop" class="size-8" />

                        <Smartphone v-else class="size-8" />
                    </div>

                    <div class="ms-3">
                        <div class="text-sm">
                            {{ session.agent.platform ? session.agent.platform : 'Unknown' }} -
                            {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
                        </div>

                        <div>
                            <div class="text-xs text-muted-foreground">
                                {{ session.ip_address }}
                                <span>&middot;</span>
                                <span v-if="session.is_current_device" class="text-emerald-500 font-semibold">
                                    This device
                                </span>
                                <span v-else>Last active {{ session.last_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </CardContent>
        <CardFooter class="border-t px-6 py-4">
            <Dialog>
                <DialogTrigger as-child>
                    <Button @click="confirmLogout"> Log Out Other Browser Sessions </Button>
                </DialogTrigger>
                <DialogContent class="sm:max-w-md">
                    <DialogHeader>
                        <DialogTitle>Log Out Other Browser Sessions</DialogTitle>
                        <DialogDescription>
                            Please enter your password to confirm you would like to log out of your other browser
                            sessions across all of your devices.
                        </DialogDescription>
                    </DialogHeader>
                    <div class="mb-6">
                        <!-- eslint-disable-next-line vuejs-accessibility/form-control-has-label -->
                        <Input
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            placeholder="Password"
                            autocomplete="current-password"
                            @keyup.enter="logoutOtherBrowserSessions"
                        />

                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                    <DialogFooter class="sm:justify-between">
                        <DialogClose as-child>
                            <Button type="button" variant="secondary"> Close </Button>
                            <Button
                                class="ms-3"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                @click="logoutOtherBrowserSessions"
                            >
                                Log Out Other Browser Sessions
                            </Button>
                        </DialogClose>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </CardFooter>
    </Card>
</template>
