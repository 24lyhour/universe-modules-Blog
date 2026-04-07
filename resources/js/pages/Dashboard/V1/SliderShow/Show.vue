<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { type VNode } from 'vue';
import { Pencil, ArrowLeft, PanelTop, Calendar } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import type { SliderShow } from '@blog/types';

defineOptions({
    layout: (h: (type: unknown, props: unknown, children: unknown) => VNode, page: VNode) =>
        h(AppLayout, { breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Slider Shows', href: '/dashboard/slider-shows' },
            { title: 'View', href: '#' },
        ]}, () => page),
});

const props = defineProps<{ sliderShow: SliderShow }>();

const formatDate = (date: string | null) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit',
    });
};
</script>

<template>
    <Head :title="`${sliderShow.title} - Slider Show`" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" @click="router.visit('/dashboard/slider-shows')">
                    <ArrowLeft class="h-4 w-4" />
                </Button>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">{{ sliderShow.title }}</h1>
                    <p class="text-muted-foreground">{{ sliderShow.description }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <Badge :variant="sliderShow.is_active ? 'default' : 'secondary'" class="text-sm">
                    {{ sliderShow.is_active ? 'Active' : 'Inactive' }}
                </Badge>
                <Button @click="router.visit(`/dashboard/slider-shows/${sliderShow.uuid}/edit`)" class="gap-2">
                    <Pencil class="h-4 w-4" /> Edit
                </Button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-base">
                            <PanelTop class="h-4 w-4" /> Preview
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="rounded-3xl p-6 text-white shadow-lg"
                            :style="{
                                background: `linear-gradient(135deg, ${sliderShow.gradient_start || '#3BC472'}, ${sliderShow.gradient_end || '#2A9D5C'})`,
                            }"
                        >
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <p class="text-xl font-bold">{{ sliderShow.title }}</p>
                                    <p class="mt-2 text-sm opacity-80">{{ sliderShow.description }}</p>
                                    <span class="mt-3 inline-block rounded-xl bg-white px-5 py-2 text-sm font-medium text-green-600">
                                        {{ sliderShow.button_text }}
                                    </span>
                                </div>
                                <div class="ml-4 flex h-20 w-20 items-center justify-center rounded-full bg-white/20">
                                    <span class="text-3xl">{{ sliderShow.icon === 'flash_on' ? '⚡' : sliderShow.icon === 'local_shipping' ? '🚚' : '🆕' }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="space-y-6">
                <Card>
                    <CardHeader><CardTitle class="text-base">Details</CardTitle></CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Icon</span>
                            <span class="text-sm font-medium font-mono">{{ sliderShow.icon }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Sort Order</span>
                            <span class="text-sm font-medium">{{ sliderShow.sort_order }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Deeplink</span>
                            <span class="text-sm font-medium truncate max-w-[150px]">{{ sliderShow.deeplink || '-' }}</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-base">
                            <Calendar class="h-4 w-4" /> Schedule
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Start</span>
                            <span class="text-sm font-medium">{{ formatDate(sliderShow.start_at) }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">End</span>
                            <span class="text-sm font-medium">{{ formatDate(sliderShow.end_at) }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Created</span>
                            <span class="text-sm font-medium">{{ formatDate(sliderShow.created_at) }}</span>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
