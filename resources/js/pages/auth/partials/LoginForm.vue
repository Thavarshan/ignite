<script setup lang="ts">
import { ref } from 'vue';
import { ArrowRight, Github, LoaderCircle } from 'lucide-vue-next';

import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { FormError } from '@/components/ui/form';
import { Checkbox } from '@/components/ui/checkbox';
import { useForm } from '@inertiajs/vue3';

const isLoading = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function onSubmit(e: Event) {
    e.preventDefault();
    isLoading.value = true;

    form.transform((data) => {
            return {
                ...data,
                remember: data.remember ? 'on' : undefined,
            };
        })
        .post('/login', {
            errorBag: 'login',
            preserveState: true,
            onSuccess: () => {
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            },
        });
}
</script>

<template>
    <div :class="cn('grid gap-6', $attrs.class ?? '')">
        <form @submit.prevent="onSubmit">
            <div class="grid gap-6">
                <div class="grid gap-1">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        placeholder="name@example.com"
                        type="email"
                        v-model="form.email"
                        auto-capitalize="none"
                        auto-complete="email"
                        auto-correct="off"
                        autofocus="true"
                        required
                        :disabled="isLoading"
                    />
                    <FormError v-if="form.errors.email" v-text="form.errors.email" />
                </div>
                <div class="grid gap-1">
                    <div class="flex items-center">
                        <Label for="password">Password</Label>
                        <a href="/forgot-password" class="ml-auto inline-block text-sm underline">Forgot your password?</a>
                    </div>
                    <Input
                        id="password"
                        placeholder="Password"
                        type="password"
                        v-model="form.password"
                        auto-complete="current-password"
                        required
                        :disabled="isLoading"
                    />
                    <FormError v-if="form.errors.password" v-text="form.errors.password" />
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center hover:cursor-pointer">
                        <Checkbox id="remember" v-model="form.remember" />
                        <Label for="remember" class="ml-2">Remember me</Label>
                    </div>
                </div>
                <Button :disabled="isLoading" type="submit">
                    <LoaderCircle v-if="isLoading" class="mr-2 h-4 w-4 animate-spin" />
                    <span>Sign in</span>
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
