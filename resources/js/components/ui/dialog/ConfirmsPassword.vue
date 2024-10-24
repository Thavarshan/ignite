<!-- eslint-disable vuejs-accessibility/click-events-have-key-events -->
<script setup lang="ts">
import { ref, reactive, nextTick } from 'vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input, InputError } from '@/components/ui/input';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogClose,
    DialogTrigger,
} from './index';
import axios from 'axios';

const emit = defineEmits(['confirmed']);

withDefaults(
    defineProps<{
        title?: string;
        content?: string;
        button?: string;
    }>(),
    {
        title: 'Confirm Password',
        content: 'For your security, please confirm your password to continue.',
        button: 'Confirm',
    },
);

const confirmingPassword = ref(false);

const form = reactive({
    password: '',
    error: '',
    processing: false,
});

const passwordInput = ref<HTMLInputElement | null>(null);

const startConfirmingPassword = () => {
    axios.get(route('password.confirmation')).then((response) => {
        if (response.data.confirmed) {
            emit('confirmed');
        } else {
            confirmingPassword.value = true;
        }
    });
};

const confirmPassword = () => {
    form.processing = true;

    axios
        .post(route('password.confirm'), {
            password: form.password,
        })
        .then(() => {
            form.processing = false;

            closeModal();
            nextTick().then(() => emit('confirmed'));
        })
        .catch((error) => {
            form.processing = false;
            form.error = error.response.data.errors.password[0];
        });
};

const closeModal = () => {
    confirmingPassword.value = false;
    form.password = '';
    form.error = '';
};
</script>

<template>
    <Dialog :open="confirmingPassword">
        <DialogTrigger as-child>
            <!-- eslint-disable-next-line vuejs-accessibility/no-static-element-interactions, vuejs-accessibility/click-events-have-key-events -->
            <span @click="startConfirmingPassword">
                <slot />
            </span>
        </DialogTrigger>

        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>

                <DialogDescription> {{ content }} </DialogDescription>
            </DialogHeader>

            <div>
                <Label for="password">Password</Label>
                <Input
                    v-model="form.password"
                    type="password"
                    id="password"
                    class="mt-1 block w-3/4"
                    placeholder="Password"
                    autocomplete="current-password"
                    @keyup.enter="confirmPassword"
                />

                <InputError :message="form.error" class="mt-2" />
            </div>
            <DialogFooter class="sm:justify-start">
                <DialogClose as-child>
                    <Button type="button" variant="secondary"> Cancel </Button>
                </DialogClose>

                <Button
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="confirmPassword"
                >
                    {{ button }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
