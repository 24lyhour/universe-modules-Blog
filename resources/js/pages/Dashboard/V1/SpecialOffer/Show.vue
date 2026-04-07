<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { type VNode } from 'vue';
import { Pencil, ArrowLeft, Tag, Calendar } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import type { SpecialOffer } from '@blog/types';

defineOptions({
    layout: (h: (type: unknown, props: unknown, children: unknown) => VNode, page: VNode) =>
        h(AppLayout, { breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Special Offers', href: '/dashboard/special-offers' },
            { title: 'View', href: '#' },
        ]}, () => page),
});

const props = defineProps<{ specialOffer: SpecialOffer }>();

const formatDate = (date: string | null) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit',
    });
};
</script>

<template>
    <Head :title="`${specialOffer.title} - Special Offer`" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" @click="router.visit('/dashboard/special-offers')">
                    <ArrowLeft class="h-4 w-4" />
                </Button>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">{{ specialOffer.title }}</h1>
                    <p class="text-muted-foreground">{{ specialOffer.subtitle }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <Badge :variant="specialOffer.is_active ? 'default' : 'secondary'" class="text-sm">
                    {{ specialOffer.is_active ? 'Active' : 'Inactive' }}
                </Badge>
                <Button @click="router.visit(`/dashboard/special-offers/${specialOffer.uuid}/edit`)" class="gap-2">
                    <Pencil class="h-4 w-4" />
                    Edit
                </Button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <!-- Preview Card -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-base">
                            <Tag class="h-4 w-4" />
                            Preview
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="rounded-xl p-6 text-white"
                            :style="{
                                background: `linear-gradient(135deg, ${specialOffer.gradient_start}, ${specialOffer.gradient_end})`,
                            }"
                        >
                            <p class="text-lg font-bold">{{ specialOffer.title }}</p>
                            <p class="text-sm opacity-80 mt-1">{{ specialOffer.subtitle }}</p>
                            <span class="mt-3 inline-block rounded bg-white/20 px-4 py-1.5 text-sm font-medium">
                                {{ specialOffer.button_text }}
                            </span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-base">Details</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Icon</span>
                            <span class="text-sm font-medium font-mono">{{ specialOffer.icon }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Sort Order</span>
                            <span class="text-sm font-medium">{{ specialOffer.sort_order }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Deeplink</span>
                            <span class="text-sm font-medium truncate max-w-[150px]">{{ specialOffer.deeplink || '-' }}</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-base">
                            <Calendar class="h-4 w-4" />
                            Schedule
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Start</span>
                            <span class="text-sm font-medium">{{ formatDate(specialOffer.start_at) }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">End</span>
                            <span class="text-sm font-medium">{{ formatDate(specialOffer.end_at) }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Created</span>
                            <span class="text-sm font-medium">{{ formatDate(specialOffer.created_at) }}</span>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
