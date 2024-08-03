<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthCard from '@/components/blocks/AuthCard.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input, InputError } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot password" />

    <AuthCard>
        <form class="mt-52" @submit.prevent="submit">
            <Card class="w-full max-w-sm">
                <CardHeader>
                    <CardTitle class="text-2xl">
                        Forgot password?
                    </CardTitle>
                    <CardDescription>
                        Enter your email address below to request a password reset link.
                    </CardDescription>
                </CardHeader>
                <CardContent class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" placeholder="max@example.com" required />
                        <InputError>{{ form.errors.email }}</InputError>
                    </div>
                </CardContent>
                <CardFooter>
                    <Button class="w-full" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Send reset link
                    </Button>
                </CardFooter>
            </Card>
            <div class="mt-4">
                <p class="text-sm text-center text-slate-500">
                    <a :href="route('login')" class="text-primary hover:underline">Back to login</a>
                </p>
            </div>
        </form>
    </AuthCard>
</template>
