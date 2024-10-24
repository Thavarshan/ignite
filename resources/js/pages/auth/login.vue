<script setup lang="ts">
import Auth from '@/layouts/auth.vue';
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Checkbox } from '@/components/ui/checkbox';
import { LoaderCircle } from 'lucide-vue-next';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />
    <Auth>
        <div class="mx-auto grid max-w-sm gap-6">
            <div class="grid gap-2 text-center">
                <h1 class="text-3xl font-bold">Login</h1>
                <p class="text-balance text-muted-foreground text-sm">
                    Enter your email address below to log in to your account. This helps us securely verify your
                    identity for access.
                </p>
            </div>
            <form @submit.prevent="submit" class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        placeholder="m@example.com"
                        required
                        autocomplete="email"
                    />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="grid gap-2">
                    <div class="flex items-center">
                        <Label for="password">Password</Label>
                        <Link :href="route('password.request')" class="ml-auto inline-block text-sm underline">
                            Forgot your password?
                        </Link>
                    </div>
                    <Input id="password" type="password" v-model="form.password" required />
                    <InputError :message="form.errors.password" />
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <Checkbox id="remember" v-model:checked="form.remember" name="remember" />
                        <Label for="remember">Remember me</Label>
                    </div>
                </div>
                <Button
                    type="submit"
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="size-5 -ml-6 mr-2 animate-spin" />
                    Login
                </Button>
                <Button variant="outline" class="w-full"> Login with Google </Button>
            </form>
            <div class="mt-4 text-center text-sm">
                Don't have an account?
                <Link :href="route('register')" class="underline"> Sign up </Link>
            </div>
        </div>
    </Auth>
</template>
