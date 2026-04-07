<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { type VNode } from 'vue';
import { Pencil, ArrowLeft, Image, Calendar } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import type { Banner } from '@blog/types';

defineOptions({
    layout: (h: (type: unknown, props: unknown, children: unknown) => VNode, page: VNode) =>
        h(AppLayout, { breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Banners', href: '/dashboard/banners' },
            { title: 'View Banner', href: '#' },
        ]}, () => page),
});

const props = defineProps<{ banner: Banner }>();

const formatDate = (date: string | null) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const handleEdit = () => {
    router.visit(`/dashboard/banners/${props.banner.uuid}/edit`);
};

const handleBack = () => {
    router.visit('/dashboard/banners');
};
</script>

<template>
    <Head :title="`${banner.name} - Banner`" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" @click="handleBack">
                    <ArrowLeft class="h-4 w-4" />
                </Button>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">{{ banner.name }}</h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <Badge :variant="banner.is_active ? 'default' : 'secondary'" class="text-sm">
                    {{ banner.is_active ? 'Active' : 'Inactive' }}
                </Badge>
                <Button @click="handleEdit" class="gap-2">
                    <Pencil class="h-4 w-4" />
                    Edit Banner
                </Button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Left Column: Preview -->
            <div class="lg:col-span-2 space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-base">
                            <Image class="h-4 w-4" />
                            Banner Images
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="banner.image_url && banner.image_url.length > 0" class="space-y-4">
                            <img
                                v-for="(img, idx) in banner.image_url"
                                :key="idx"
                                :src="img"
                                :alt="`${banner.name} - ${idx + 1}`"
                                class="w-full rounded-lg object-cover"
                                style="aspect-ratio: 1200/500;"
                            />
                        </div>
                        <div
                            v-else
                            class="flex h-48 items-center justify-center rounded-lg border-2 border-dashed"
                        >
                            <div class="text-center text-muted-foreground">
                                <Image class="mx-auto h-12 w-12 mb-2" />
                                <p>No images uploaded</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Right Column: Details -->
            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-base">Details</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Status</span>
                            <Badge :variant="banner.is_active ? 'default' : 'secondary'">
                                {{ banner.is_active ? 'Active' : 'Inactive' }}
                            </Badge>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Images</span>
                            <span class="text-sm font-medium">{{ banner.image_url?.length ?? 0 }}</span>
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
                            <span class="text-sm font-medium">{{ formatDate(banner.start_at) }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">End</span>
                            <span class="text-sm font-medium">{{ formatDate(banner.end_at) }}</span>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted-foreground">Created</span>
                            <span class="text-sm font-medium">{{ formatDate(banner.created_at) }}</span>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
