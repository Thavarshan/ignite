<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import axios from 'axios';
import { Flag } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    url: string;
}>();

const open = ref(false);
const reason = ref('');
const description = ref('');
const submitting = ref(false);
const error = ref('');

function reset() {
    reason.value = '';
    description.value = '';
    error.value = '';
}

async function submit() {
    if (!reason.value) {
        return;
    }

    submitting.value = true;
    error.value = '';

    try {
        await axios.post(props.url, {
            reason: reason.value,
            description: description.value || null,
        });
        open.value = false;
        reset();
    } catch (e: unknown) {
        if (axios.isAxiosError(e) && e.response?.status === 422) {
            error.value =
                e.response.data.message ??
                'You have already flagged this content.';
        } else {
            error.value = 'Something went wrong. Please try again.';
        }
    } finally {
        submitting.value = false;
    }
}
</script>

<template>
    <Dialog
        v-model:open="open"
        @update:open="(val: boolean) => !val && reset()"
    >
        <DialogTrigger as-child>
            <Button
                variant="ghost"
                size="sm"
                class="h-8 gap-1.5 px-2 text-xs text-muted-foreground"
            >
                <Flag class="size-3.5" />
                Report
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Report content</DialogTitle>
                <DialogDescription>
                    Help us keep the community safe by reporting content that
                    violates our guidelines.
                </DialogDescription>
            </DialogHeader>

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Reason</label>
                    <Select v-model="reason">
                        <SelectTrigger>
                            <SelectValue placeholder="Select a reason" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="spam">Spam</SelectItem>
                            <SelectItem value="inappropriate"
                                >Inappropriate</SelectItem
                            >
                            <SelectItem value="off-topic">Off-topic</SelectItem>
                            <SelectItem value="other">Other</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium"
                        >Description (optional)</label
                    >
                    <Textarea
                        v-model="description"
                        placeholder="Provide additional details..."
                        rows="3"
                    />
                </div>

                <p v-if="error" class="text-sm text-destructive">{{ error }}</p>
            </div>

            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="outline" :disabled="submitting"
                        >Cancel</Button
                    >
                </DialogClose>
                <Button :disabled="!reason || submitting" @click="submit">
                    {{ submitting ? 'Submitting...' : 'Submit Report' }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
