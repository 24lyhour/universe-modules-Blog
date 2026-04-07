<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, type VNode } from 'vue';
import { PanelTop, Plus, Pencil, Trash2, Eye } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent } from '@/components/ui/card';
import { Switch } from '@/components/ui/switch';
import { ModalConfirm, TableReusable, StatsCard } from '@/components/shared';
import type { SliderShowIndexProps, SliderShow } from '@blog/types';

defineOptions({
    layout: (h: (type: unknown, props: unknown, children: unknown) => VNode, page: VNode) =>
        h(AppLayout, { breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Slider Shows', href: '#' },
        ]}, () => page),
});

const props = defineProps<SliderShowIndexProps>();

const search = ref(props.filters.search || '');
const isActive = ref(props.filters.is_active || 'all');

const showDeleteModal = ref(false);
const itemToDelete = ref<SliderShow | null>(null);
const isDeleting = ref(false);

const columns = [
    { key: 'preview', label: 'Preview', sortable: false },
    { key: 'title', label: 'Title', sortable: true },
    { key: 'button_text', label: 'Button', sortable: false },
    { key: 'is_active', label: 'Status', sortable: true },
    { key: 'actions', label: '', sortable: false },
];

const actions = [
    {
        label: 'View',
        icon: Eye,
        onClick: (item: SliderShow) => router.visit(`/dashboard/slider-shows/${item.uuid}`),
    },
    {
        label: 'Edit',
        icon: Pencil,
        onClick: (item: SliderShow) => router.visit(`/dashboard/slider-shows/${item.uuid}/edit`),
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        onClick: (item: SliderShow) => { itemToDelete.value = item; showDeleteModal.value = true; },
    },
];

const stats = computed(() => ({
    total: props.stats?.total ?? 0,
    active: props.stats?.active ?? 0,
    inactive: props.stats?.inactive ?? 0,
}));

const applyFilters = () => {
    router.get('/dashboard/slider-shows', {
        search: search.value || undefined,
        is_active: isActive.value !== 'all' ? isActive.value : undefined,
    }, { preserveState: true, replace: true });
};

const clearFilters = () => { search.value = ''; isActive.value = 'all'; applyFilters(); };

const handleSearch = (value: string) => { search.value = value; applyFilters(); };

const handlePageChange = (page: number) => {
    router.get('/dashboard/slider-shows', { ...props.filters, page }, { preserveState: true, replace: true });
};

const handleStatusToggle = (item: SliderShow, newStatus: boolean) => {
    router.put(`/dashboard/slider-shows/${item.uuid}/toggle-active`, { status: newStatus }, {
        preserveState: true, preserveScroll: true,
    });
};

const handleDelete = () => {
    if (!itemToDelete.value) return;
    isDeleting.value = true;
    router.delete(`/dashboard/slider-shows/${itemToDelete.value.uuid}`, {
        onFinish: () => { isDeleting.value = false; showDeleteModal.value = false; itemToDelete.value = null; },
    });
};
</script>

<template>
    <Head title="Slider Shows" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Slider Shows</h1>
                <p class="text-muted-foreground">Manage promo carousel slides for customer app</p>
            </div>
            <Button @click="router.visit('/dashboard/slider-shows/create')" class="gap-2">
                <Plus class="h-4 w-4" /> Add Slide
            </Button>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <StatsCard title="Total Slides" :value="stats.total" :icon="PanelTop" variant="info" />
            <StatsCard title="Active" :value="stats.active" :icon="PanelTop" variant="success" />
            <StatsCard title="Inactive" :value="stats.inactive" :icon="PanelTop" variant="secondary" />
        </div>

        <Card>
            <CardContent class="p-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <Input v-model="search" placeholder="Search slides..." @keyup.enter="applyFilters" />
                    </div>
                    <Select v-model="isActive" @update:model-value="applyFilters">
                        <SelectTrigger class="w-[120px]"><SelectValue placeholder="Status" /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Status</SelectItem>
                            <SelectItem value="true">Active</SelectItem>
                            <SelectItem value="false">Inactive</SelectItem>
                        </SelectContent>
                    </Select>
                    <Button variant="outline" size="sm" @click="clearFilters">Clear</Button>
                </div>
            </CardContent>
        </Card>

        <TableReusable
            :data="sliderShows.data"
            :columns="columns"
            :actions="actions"
            :pagination="sliderShows.meta"
            @page-change="handlePageChange"
            @search="handleSearch"
        >
            <template #cell-preview="{ item }">
                <div
                    class="flex h-10 w-28 items-center justify-center rounded-lg text-white text-xs font-medium"
                    :style="{
                        background: `linear-gradient(135deg, ${item.gradient_start || '#3BC472'}, ${item.gradient_end || '#2A9D5C'})`,
                    }"
                >
                    {{ item.title }}
                </div>
            </template>
            <template #cell-title="{ item }">
                <div>
                    <span class="font-medium">{{ item.title }}</span>
                    <p class="text-xs text-muted-foreground line-clamp-1">{{ item.description || '' }}</p>
                </div>
            </template>
            <template #cell-button_text="{ item }">
                <span class="text-sm">{{ item.button_text }}</span>
            </template>
            <template #cell-sort_order="{ item }">
                <span class="text-sm">{{ item.sort_order }}</span>
            </template>
            <template #cell-is_active="{ item }">
                <div class="flex items-center gap-2" @click.stop>
                    <Switch :model-value="item.is_active" @update:model-value="handleStatusToggle(item, $event)" />
                    <span class="text-sm text-muted-foreground">{{ item.is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </template>
        </TableReusable>
    </div>

    <ModalConfirm
        v-model:open="showDeleteModal"
        title="Delete Slider Show"
        :description="`Are you sure you want to delete '${itemToDelete?.title}'?`"
        confirm-text="Delete" variant="danger" :loading="isDeleting" @confirm="handleDelete"
    />
</template>
