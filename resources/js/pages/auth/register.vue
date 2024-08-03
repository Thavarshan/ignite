<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthCard from '@/components/blocks/AuthCard.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input, InputError } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { UserCreate } from '@/types/user';

const page = usePage();

const form = useForm<UserCreate>({
    firstname: '',
    lastname: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
}
</script>

<template>
    <Head title="Create account" />

    <AuthCard>
        <form class="mt-52" @submit.prevent="submit">
            <Card class="w-full max-w-sm">
                <CardHeader>
                    <CardTitle class="text-2xl">
                        Register
                    </CardTitle>
                    <CardDescription>
                        Enter your details below to create an account.
                    </CardDescription>
                </CardHeader>
                <CardContent class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="firstname">First name</Label>
                        <Input id="firstname" type="firstname" placeholder="John" required />
                        <InputError>{{ form.errors.firstname }}</InputError>
                    </div>
                    <div class="grid gap-2">
                        <Label for="lastname">Last name</Label>
                        <Input id="lastname" type="lastname" placeholder="Doe" required />
                        <InputError>{{ form.errors.lastname }}</InputError>
                    </div>
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
                        Create account
                    </Button>
                </CardFooter>
            </Card>
            <div class="mt-4">
                <p class="text-sm text-center text-slate-500">
                    Already have an account? <a :href="route('login')" class="text-primary hover:underline">Sign in</a>
                </p>
            </div>
        </form>
    </AuthCard>
</template>
