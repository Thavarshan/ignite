<script setup lang="ts">
import { useToast } from '@/components/ui/toast/use-toast';
import { Button } from '@/components/ui/button';
import { Input, InputError } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import type { User, UpdateUser } from '@/types/user';
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    user: User;
}>();

const { toast } = useToast();

const form = useForm<UpdateUser>({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(false);
const photoPreview = ref<string | ArrayBuffer | null>(null);
const photoInput = ref<HTMLInputElement | null>(null);

const updateProfileInformation = () => {
    if (photoInput.value && photoInput.value.files) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => {
            clearPhotoFileInput();

            toast({
                title: 'Profile information updated.',
                description: 'Your profile information has been updated successfully.',
            });
        },
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    if (photoInput.value) {
        photoInput.value.click();
    }
};

const updatePhotoPreview = () => {
    if (!photoInput.value || !photoInput.value.files) return;
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        if (e.target) {
            photoPreview.value = e.target.result;
        }
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();

            toast({
                title: 'Profile photo removed.',
                description: 'Your profile photo has been removed successfully.',
            });
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = '';
    }
};
</script>

<template>
    <form @submit.prevent="updateProfileInformation">
        <Card>
            <CardHeader>
                <CardTitle>Profile Information</CardTitle>
                <CardDescription> Update your account's profile information and email address. </CardDescription>
            </CardHeader>
            <CardContent>
                <!-- Profile Photo -->
                <div class="flex gap-x-4 items-center">
                    <div>
                        <!-- Profile Photo File Input -->
                        <input id="photo" ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview" />

                        <Label for="photo">Photo</Label>

                        <div class="flex gap-x-4 items-center">
                            <div>
                                <!-- Current Profile Photo -->
                                <div v-show="!photoPreview" class="mt-2">
                                    <img
                                        :src="user.profile_photo_url"
                                        :alt="user.name"
                                        class="rounded-full h-20 w-20 object-cover"
                                    />
                                </div>

                                <!-- New Profile Photo Preview -->
                                <div v-show="photoPreview" class="mt-2">
                                    <span
                                        class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                                        :style="'background-image: url(\'' + photoPreview + '\');'"
                                    />
                                </div>
                            </div>

                            <div>
                                <Button
                                    varient="secondary"
                                    class="mt-2 me-2"
                                    type="button"
                                    @click.prevent="selectNewPhoto"
                                >
                                    Select a new photo
                                </Button>

                                <Button
                                    varient="secondary"
                                    v-if="user.profile_photo_path"
                                    type="button"
                                    class="mt-2"
                                    @click.prevent="deletePhoto"
                                >
                                    Remove photo
                                </Button>

                                <InputError :message="form.errors.photo" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-6 gap-4 w-full">
                    <!-- Name -->
                    <div class="col-span-4">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autocomplete="name"
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="col-span-4">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                            autocomplete="username"
                        />
                        <InputError :message="form.errors.email" class="mt-2" />

                        <div v-if="user.email_verified_at === null">
                            <p class="text-xs mt-2">
                                Your email address is unverified.

                                <Link
                                    :href="route('verification.send')"
                                    method="post"
                                    as="button"
                                    class="underline text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 dark:focus:ring-offset-gray-800"
                                    @click.prevent="sendEmailVerification"
                                >
                                    Click here to re-send the verification email.
                                </Link>
                            </p>

                            <div
                                v-show="verificationLinkSent"
                                class="mt-2 font-medium text-xs text-green-600 dark:text-green-400"
                            >
                                A new verification link has been sent to your email address.
                            </div>
                        </div>
                    </div>
                </div>
            </CardContent>
            <CardFooter class="border-t px-6 py-4">
                <Button type="submit">Save</Button>
            </CardFooter>
        </Card>
    </form>
</template>
