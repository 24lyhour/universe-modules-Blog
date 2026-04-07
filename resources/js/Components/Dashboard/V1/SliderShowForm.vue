<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import type { SliderShowFormData } from '@blog/types';
import TiptapEditor from '@/components/TiptapEditor.vue';

interface Props {
    errors?: Partial<Record<keyof SliderShowFormData, string>>;
}

defineProps<Props>();

const form = defineModel<SliderShowFormData>({ required: true });
</script>

<template>
    <div class="space-y-6">
        <!-- Title -->
        <div class="space-y-2">
            <Label for="title">Title <span class="text-destructive">*</span></Label>
            <Input
                id="title"
                v-model="form.title"
                placeholder="e.g. Free Shipping"
                :class="{ 'border-destructive': errors?.title }"
            />
            <p v-if="errors?.title" class="text-sm text-destructive">{{ errors.title }}</p>
        </div>

        <!-- Description -->
        <div class="space-y-2">
            <Label for="description">Description</Label>
            <TiptapEditor
                id="description"
                v-model="form.description"
                placeholder="e.g. On all orders over $50 this weekend only."
                rows="3"
            />
        </div>

        <!-- Button Text -->
        <div class="space-y-2">
            <Label for="button_text">Button Text</Label>
            <Input
                id="button_text"
                v-model="form.button_text"
                placeholder="e.g. Order Now"
            />
        </div>

        <!-- Deeplink -->
        <div class="space-y-2">
            <Label for="deeplink">Deeplink / URL</Label>
            <Input
                id="deeplink"
                v-model="form.deeplink"
                placeholder="e.g. app://products or https://..."
            />
        </div>

        <!-- Gradient Colors -->
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <Label for="gradient_start">Gradient Start</Label>
                <div class="flex items-center gap-2">
                    <input
                        id="gradient_start"
                        v-model="form.gradient_start"
                        type="color"
                        class="h-10 w-10 cursor-pointer rounded border p-0.5"
                    />
                    <Input v-model="form.gradient_start" placeholder="#3BC472" class="flex-1" />
                </div>
            </div>
            <div class="space-y-2">
                <Label for="gradient_end">Gradient End</Label>
                <div class="flex items-center gap-2">
                    <input
                        id="gradient_end"
                        v-model="form.gradient_end"
                        type="color"
                        class="h-10 w-10 cursor-pointer rounded border p-0.5"
                    />
                    <Input v-model="form.gradient_end" placeholder="#2A9D5C" class="flex-1" />
                </div>
            </div>
        </div>

        <!-- Preview -->
        <div class="space-y-2">
            <Label>Preview</Label>
            <div
                class="rounded-3xl p-5 text-white"
                :style="{
                    background: `linear-gradient(135deg, ${form.gradient_start}, ${form.gradient_end})`,
                }"
            >
                <p class="text-lg font-bold">{{ form.title || 'Title' }}</p>
                <p class="mt-1 text-sm opacity-80">{{ form.description || 'Description' }}</p>
                <span class="mt-3 inline-block rounded-xl bg-white/90 px-5 py-2 text-sm font-medium" :style="{ color: form.gradient_start }">
                    {{ form.button_text || 'Shop Now' }}
                </span>
            </div>
        </div>

        <!-- Icon + Sort Order -->
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <Label for="icon">Icon Name</Label>
                <Input
                    id="icon"
                    v-model="form.icon"
                    placeholder="e.g. flash_on"
                />
                <p class="text-xs text-muted-foreground">Flutter Material icon name</p>
            </div>
            <div class="space-y-2">
                <Label for="sort_order">Sort Order</Label>
                <Input
                    id="sort_order"
                    v-model.number="form.sort_order"
                    type="number"
                    min="0"
                    placeholder="0"
                />
            </div>
        </div>

        <!-- Schedule -->
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <Label for="start_at">Start Date</Label>
                <Input id="start_at" v-model="form.start_at" type="datetime-local" />
            </div>
            <div class="space-y-2">
                <Label for="end_at">End Date</Label>
                <Input id="end_at" v-model="form.end_at" type="datetime-local" />
                <p v-if="errors?.end_at" class="text-sm text-destructive">{{ errors.end_at }}</p>
            </div>
        </div>

        <!-- Active -->
        <div class="flex items-center justify-between rounded-lg border p-4">
            <div>
                <Label>Active</Label>
                <p class="text-sm text-muted-foreground">Enable or disable this slider</p>
            </div>
            <Switch
                :model-value="form.is_active"
                @update:model-value="(v: boolean) => form.is_active = v"
            />
        </div>
    </div>
</template>
