<script setup lang="ts">
import { ref } from 'vue';
import { ArrowRight, Github, LoaderCircle } from 'lucide-vue-next';

import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';

const isLoading = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

function onSubmit(e: Event) {
    e.preventDefault();
    isLoading.value = true;

    form.post(route('register'), {
        errorBag: 'register',
        preserveState: true,
        onSuccess: () => {
            isLoading.value = false;
        },
        onError: () => {
            isLoading.value = false;
        },
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <div :class="cn('grid gap-6', $attrs.class ?? '')">
        <form @submit.prevent="onSubmit">
            <div class="grid gap-6">
                <div class="grid gap-1">
                    <Label for="name">Full name</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="John Doe"
                        auto-complete="name"
                        auto-capitalize="words"
                        auto-correct="off"
                        autofocus="true"
                        :disabled="isLoading"
                        required
                    />
                </div>
                <div class="grid gap-1">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        placeholder="name@example.com"
                        type="email"
                        auto-capitalize="none"
                        auto-complete="email"
                        auto-correct="off"
                        :disabled="isLoading"
                        required
                    />
                </div>
                <div class="grid gap-1">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        v-model="form.password"
                        placeholder="Password"
                        type="password"
                        auto-complete="current-password"
                        :disabled="isLoading"
                        required
                    />
                </div>
                <div class="grid gap-1">
                    <Label for="confirmPassword">Confirm password</Label>
                    <Input
                        id="confirmPassword"
                        v-model="form.password_confirmation"
                        placeholder="Confirm password"
                        type="password"
                        auto-complete="new-password"
                        :disabled="isLoading"
                        required
                    />
                </div>
                <Button :disabled="isLoading" type="submit">
                    <LoaderCircle v-if="isLoading" class="mr-2 h-4 w-4 animate-spin" />
                    <span>Sign up</span>
                    <ArrowRight class="ml-2 h-4 w-4" />
                </Button>
            </div>
        </form>
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <span class="w-full border-t" />
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-background px-2 text-muted-foreground"> Or continue with </span>
            </div>
        </div>
        <Button variant="outline" type="button" :disabled="isLoading">
            <LoaderCircle v-if="isLoading" class="mr-2 h-4 w-4 animate-spin" />
            <Github v-else class="mr-2 h-4 w-4" />
            <span>GitHub</span>
        </Button>
    </div>
</template>
