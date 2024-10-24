<script setup lang="ts">
import { useToast } from '@/components/ui/toast/use-toast';
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import type { UpdatePassword } from '@/types/user';
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';

const { toast } = useToast();
const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm<UpdatePassword>({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => {
            form.reset();

            toast({
                title: 'Password updated.',
                description: 'Your password has been updated successfully.',
            });
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                if (passwordInput.value) {
                    passwordInput.value.focus();
                }
            }

            if (form.errors.current_password) {
                form.reset('current_password');
                if (currentPasswordInput.value) {
                    currentPasswordInput.value.focus();
                }
            }
        },
    });
};
</script>

<template>
    <form @submit.prevent="updatePassword">
        <Card>
            <CardHeader>
                <CardTitle>Update Password</CardTitle>
                <CardDescription>
                    Ensure your account is using a long, random password to stay secure.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="grid grid-cols-6 gap-4 w-full">
                    <div class="col-span-6 sm:col-span-4">
                        <Label for="current_password">Current Password</Label>
                        <Input
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="current-password"
                        />
                        <InputError :message="form.errors.current_password" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <Label for="password">New Password</Label>
                        <Input
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                        />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <Label for="password_confirmation">Confirm Password</Label>
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                        />
                        <InputError :message="form.errors.password_confirmation" class="mt-2" />
                    </div>
                </div>
            </CardContent>
            <CardFooter class="border-t px-6 py-4">
                <Button type="submit">Save</Button>
            </CardFooter>
        </Card>
    </form>
</template>
