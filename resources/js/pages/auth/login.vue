<script setup lang="ts">
import LoginForm from './partials/LoginForm.vue';
import { cn } from '@/lib/utils';
import { buttonVariants } from '@/components/ui/button';
import Logo from '@/components/ui/logo/Logo.vue';
import Auth from '@/layouts/auth.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Login" />

    <auth>
        <div class="container relative hidden h-full flex-col items-center justify-center md:grid lg:max-w-none lg:grid-cols-2 lg:px-0">
            <Link :href="route('register')" :class="cn(buttonVariants({ variant: 'ghost' }), 'absolute right-4 top-4 md:right-8 md:top-8')">
                Register
            </Link>
            <div class="relative hidden h-full flex-col bg-muted p-10 text-white dark:border-r lg:flex">
                <div class="absolute inset-0 bg-blue-900" />
                <div class="relative z-20 flex items-center text-lg font-medium">
                    <Logo class="mr-1 h-8 w-8 text-white" href="/" />
                    <span>Ignite</span>
                </div>
                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <p class="text-lg max-w-xl">
                            &ldquo;This library has saved me countless hours of work and helped me deliver stunning
                            designs to my clients faster than ever before.&rdquo;
                        </p>
                        <footer class="text-sm">Sofia Davis</footer>
                    </blockquote>
                </div>
            </div>
            <div class="lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <div class="flex flex-col space-y-2 text-center">
                        <h1 class="text-2xl font-semibold tracking-tight">Sign in to your account</h1>
                        <p class="text-sm text-muted-foreground">
                            Enter your email and password to sign in to your account.
                        </p>
                    </div>
                    <LoginForm />
                    <p class="px-8 text-center text-sm text-muted-foreground">
                        Don't have an account?
                        <a href="/register" class="underline underline-offset-4 hover:text-primary">Sign up</a>
                    </p>
                </div>
            </div>
        </div>
    </auth>
</template>
