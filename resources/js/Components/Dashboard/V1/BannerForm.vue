<script setup lang="ts">
import { computed } from 'vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import { Separator } from '@/components/ui/separator';
import { ImageUpload } from '@/components/shared';
import TiptapEditor from '@/components/TiptapEditor.vue';
import type { BannerFormData } from '@blog/types';

interface Props {
    errors?: Partial<Record<keyof BannerFormData, string>>;
}

defineProps<Props>();

const form = defineModel<BannerFormData>({ required: true });

const editorContent = computed({
    get: () => form.value.description ?? '',
    set: (val: string) => { form.value.description = val; },
});
</script>

<template>
    <div class="space-y-6">
        <!-- Basic Information Section -->
        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-medium">Basic Information</h3>
                <p class="text-sm text-muted-foreground">Enter the banner details</p>
            </div>
            <Separator />

            <div class="grid gap-4">
                <div class="space-y-2">
                    <Label for="name">Name <span class="text-destructive">*</span></Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        placeholder="Enter banner name"
                        :class="{ 'border-destructive': errors?.name }"
                    />
                    <p v-if="errors?.name" class="text-sm text-destructive">{{ errors.name }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="description">Description</Label>
                    <TiptapEditor
                        v-model="editorContent"
                        placeholder="Enter banner description with formatting..."
                        min-height="150px"
                        max-height="300px"
                    />
                    <p class="text-xs text-muted-foreground">Displayed below the banner on the customer app</p>
                    <p v-if="errors?.description" class="text-sm text-destructive">{{ errors.description }}</p>
                </div>
            </div>
        </div>

        <!-- Banner Images Section -->
        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-medium">Banner Images</h3>
                <p class="text-sm text-muted-foreground">Upload banner images (recommended: 1200 x 500 px)</p>
            </div>
            <Separator />

            <ImageUpload
                v-model="form.image_url"
                label=""
                :multiple="true"
                :max-files="5"
                :max-size="5"
                :error="errors?.image_url"
            />
        </div>

        <!-- Schedule Section -->
        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-medium">Schedule & Status</h3>
                <p class="text-sm text-muted-foreground">Set when this banner should be visible</p>
            </div>
            <Separator />

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <Label for="start_at">Start Date</Label>
                    <Input
                        id="start_at"
                        v-model="form.start_at"
                        type="datetime-local"
                    />
                </div>
                <div class="space-y-2">
                    <Label for="end_at">End Date</Label>
                    <Input
                        id="end_at"
                        v-model="form.end_at"
                        type="datetime-local"
                    />
                    <p v-if="errors?.end_at" class="text-sm text-destructive">{{ errors.end_at }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between rounded-lg border p-4">
                <div>
                    <Label>Active</Label>
                    <p class="text-sm text-muted-foreground">Enable or disable this banner</p>
                </div>
                <Switch
                    :model-value="form.is_active"
                    @update:model-value="(v: boolean) => form.is_active = v"
                />
            </div>
        </div>
    </div>
</template>
