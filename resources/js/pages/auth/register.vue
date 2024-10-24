<script setup lang="ts">
import Auth from '@/layouts/auth.vue';
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Checkbox } from '@/components/ui/checkbox';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Create Account" />
    <Auth>
        <div class="mx-auto grid max-w-sm gap-6">
            <div class="grid gap-2 text-center">
                <h1 class="text-3xl font-bold">Create Account</h1>
                <p class="text-balance text-muted-foreground text-sm">
                    Enter your email address below to create an account. This helps us securely verify your identity for
                    access.
                </p>
            </div>
            <form @submit.prevent="submit" class="grid gap-4">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        type="name"
                        v-model="form.name"
                        placeholder="John Doe"
                        required
                        autocomplete="name"
                    />
                    <InputError :message="form.errors.email" />
                </div>
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
                    <Label for="password">Password</Label>
                    <Input id="password" type="password" v-model="form.password" required />
                    <InputError :message="form.errors.password" />
                </div>
                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm Password</Label>
                    <Input id="password_confirmation" type="password" v-model="form.password_confirmation" required />
                    <InputError :message="form.errors.password_confirmation" />
                </div>
                <div class="flex items-center space-x-2">
                    <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
                    <Label for="terms">
                        I agree to the <Link :href="route('terms.show')" class="underline">terms</Link> and
                        <Link :href="route('policy.show')" class="underline">conditions</Link>.</Label
                    >
                </div>
                <Button
                    type="submit"
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="size-5 -ml-6 mr-2 animate-spin" />
                    Register
                </Button>
                <Button variant="outline" class="w-full"> Register with Google </Button>
            </form>
            <div class="mt-4 text-center text-sm">
                Already have an account?
                <Link :href="route('login')" class="underline"> Sign in </Link>
            </div>
        </div>
    </Auth>
</template>
