<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthCard from '@/components/blocks/AuthCard.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input, InputError } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset password" />

    <AuthCard>
        <form class="mt-52" @submit.prevent="submit">
            <Card class="w-full max-w-sm">
                <CardHeader>
                    <CardTitle class="text-2xl">
                        Reset password
                    </CardTitle>
                    <CardDescription>
                        Make sure your new password is at least 8 characters long, and contains at least one uppercase letter, one lowercase letter, and one number.
                    </CardDescription>
                </CardHeader>
                <CardContent class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" placeholder="max@example.com" required />
                        <InputError>{{ form.errors.email }}</InputError>
                    </div>
                    <div class="grid gap-2">
                        <Label for="password">Password</Label>
                        <Input id="password" type="password" placeholder="Password" required />
                        <div>{{ form.errors.password }}</div>
                    </div>
                    <div class="grid gap-2">
                        <Label for="password-confirmation">Confirm password</Label>
                        <Input id="password-confirmation" type="password" placeholder="Confirm password" required />
                        <div>{{ form.errors.password_confirmation }}</div>
                    </div>
                </CardContent>
                <CardFooter>
                    <Button class="w-full" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Reset password
                    </Button>
                </CardFooter>
            </Card>
        </form>
    </AuthCard>
</template>
