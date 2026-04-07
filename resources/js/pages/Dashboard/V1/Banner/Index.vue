<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, type VNode } from 'vue';
import { Image, Plus, Pencil, Trash2, Eye } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent } from '@/components/ui/card';
import { Switch } from '@/components/ui/switch';
import { ModalConfirm, TableReusable, StatsCard } from '@/components/shared';
import type { BannerIndexProps, Banner } from '@blog/types';

defineOptions({
    layout: (h: (type: unknown, props: unknown, children: unknown) => VNode, page: VNode) =>
        h(AppLayout, { breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Banners', href: '#' },
        ]}, () => page),
});

const props = defineProps<BannerIndexProps>();

// Filters
const search = ref(props.filters.search || '');
const isActive = ref(props.filters.is_active || 'all');

// Delete modal
const showDeleteModal = ref(false);
const bannerToDelete = ref<Banner | null>(null);
const isDeleting = ref(false);

// Table columns
const columns = [
    { key: 'name', label: 'Banner', sortable: true },
    { key: 'schedule', label: 'Schedule', sortable: false },
    { key: 'is_active', label: 'Status', sortable: true },
    { key: 'actions', label: '', sortable: false },
];

// Table actions
const actions = [
    {
        label: 'View',
        icon: Eye,
        onClick: (banner: Banner) => router.visit(`/dashboard/banners/${banner.uuid}`),
    },
    {
        label: 'Edit',
        icon: Pencil,
        onClick: (banner: Banner) => router.visit(`/dashboard/banners/${banner.uuid}/edit`),
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        onClick: (banner: Banner) => confirmDelete(banner),
    },
];

// Stats from backend
const stats = computed(() => ({
    total: props.stats?.total ?? 0,
    active: props.stats?.active ?? 0,
    inactive: props.stats?.inactive ?? 0,
}));

const applyFilters = () => {
    router.get('/dashboard/banners', {
        search: search.value || undefined,
        is_active: isActive.value !== 'all' ? isActive.value : undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    search.value = '';
    isActive.value = 'all';
    applyFilters();
};

const handleSearch = (value: string) => {
    search.value = value;
    applyFilters();
};

const handlePageChange = (page: number) => {
    router.get('/dashboard/banners', {
        ...props.filters,
        page,
    }, {
        preserveState: true,
        replace: true,
    });
};

const handleStatusToggle = (banner: Banner, newStatus: boolean) => {
    router.put(`/dashboard/banners/${banner.uuid}/toggle-active`, {
        status: newStatus,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const confirmDelete = (banner: Banner) => {
    bannerToDelete.value = banner;
    showDeleteModal.value = true;
};

const handleDelete = () => {
    if (!bannerToDelete.value) return;

    isDeleting.value = true;
    router.delete(`/dashboard/banners/${bannerToDelete.value.uuid}`, {
        onFinish: () => {
            isDeleting.value = false;
            showDeleteModal.value = false;
            bannerToDelete.value = null;
        },
    });
};

const handleCreate = () => {
    router.visit('/dashboard/banners/create');
};

const formatDate = (date: string | null) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="Banners" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Banners</h1>
                <p class="text-muted-foreground">Manage promotional banners</p>
            </div>
            <Button @click="handleCreate" class="gap-2">
                <Plus class="h-4 w-4" />
                Add Banner
            </Button>
        </div>

        <!-- Stats Cards -->
        <div class="grid gap-4 md:grid-cols-3">
            <StatsCard
                title="Total Banners"
                :value="stats.total"
                :icon="Image"
                variant="info"
            />
            <StatsCard
                title="Active"
                :value="stats.active"
                :icon="Image"
                variant="success"
            />
            <StatsCard
                title="Inactive"
                :value="stats.inactive"
                :icon="Image"
                variant="secondary"
            />
        </div>

        <!-- Filters -->
        <Card>
            <CardContent class="p-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <Input
                            v-model="search"
                            placeholder="Search banners..."
                            @keyup.enter="applyFilters"
                        />
                    </div>
                    <Select v-model="isActive" @update:model-value="applyFilters">
                        <SelectTrigger class="w-[120px]">
                            <SelectValue placeholder="Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Status</SelectItem>
                            <SelectItem value="true">Active</SelectItem>
                            <SelectItem value="false">Inactive</SelectItem>
                        </SelectContent>
                    </Select>
                    <Button variant="outline" size="sm" @click="clearFilters">
                        Clear
                    </Button>
                </div>
            </CardContent>
        </Card>

        <!-- Table -->
        <TableReusable
            :data="bannerItems.data"
            :columns="columns"
            :actions="actions"
            :pagination="bannerItems.meta"
            @page-change="handlePageChange"
            @search="handleSearch"
        >
            <template #cell-name="{ item }">
                <div class="flex items-center gap-3">
                    <img
                        v-if="item.image_url && item.image_url.length > 0"
                        :src="item.image_url[0]"
                        :alt="item.name"
                        class="h-8 w-20 rounded object-cover"
                    />
                    <div
                        v-else
                        class="flex h-8 w-20 items-center justify-center rounded bg-muted"
                    >
                        <Image class="h-4 w-4 text-muted-foreground" />
                    </div>
                    <span class="font-medium">{{ item.name }}</span>
                </div>
            </template>

            <template #cell-schedule="{ item }">
                <div class="text-sm text-muted-foreground">
                    <span v-if="item.start_at || item.end_at">
                        {{ formatDate(item.start_at) }} — {{ formatDate(item.end_at) }}
                    </span>
                    <span v-else>Always</span>
                </div>
            </template>

            <template #cell-is_active="{ item }">
                <div class="flex items-center gap-2" @click.stop>
                    <Switch
                        :model-value="item.is_active"
                        @update:model-value="handleStatusToggle(item, $event)"
                    />
                    <span class="text-sm text-muted-foreground">
                        {{ item.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </template>
        </TableReusable>
    </div>

    <!-- Delete Confirmation Modal -->
    <ModalConfirm
        v-model:open="showDeleteModal"
        title="Delete Banner"
        :description="`Are you sure you want to delete '${bannerToDelete?.name}'? This action cannot be undone.`"
        confirm-text="Delete"
        variant="danger"
        :loading="isDeleting"
        @confirm="handleDelete"
    />
</template>
