<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthCard from '@/components/blocks/AuthCard.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input, InputError } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { UserLogin } from '@/types/user';

defineProps({
    status: String,
});

const form = useForm<UserLogin>({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
}
</script>

<template>
    <Head title="Sign in" />

    <AuthCard>
        <form class="mt-52" @submit.prevent="submit">
            <Card class="w-full max-w-sm">
                <CardHeader>
                    <CardTitle class="text-2xl">
                        Login
                    </CardTitle>
                    <CardDescription>
                        Enter your email below to login to your account.
                    </CardDescription>
                </CardHeader>
                <CardContent class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" placeholder="max@example.com" required />
                        <InputError>{{ form.errors.email }}</InputError>
                    </div>
                    <div class="grid gap-2">
                        <div class="flex justify-between items-baseline">
                            <Label for="password">Password</Label>
                            <a :href="route('password.request')" class="text-sm text-primary hover:underline">Forgot password?</a>
                        </div>
                        <Input id="password" type="password" placeholder="Password" required />
                        <InputError>{{ form.errors.password }}</InputError>
                    </div>
                </CardContent>
                <CardFooter>
                    <Button class="w-full" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Sign in
                    </Button>
                </CardFooter>
            </Card>
            <div class="mt-4">
                <p class="text-sm text-center text-slate-500">
                    Don't have an account? <a :href="route('register')" class="text-primary hover:underline">Create one</a>
                </p>
            </div>
        </form>
    </AuthCard>
</template>
