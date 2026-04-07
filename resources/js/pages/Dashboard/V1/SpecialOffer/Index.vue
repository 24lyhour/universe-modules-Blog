<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, type VNode } from 'vue';
import { Tag, Plus, Pencil, Trash2, Eye } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent } from '@/components/ui/card';
import { Switch } from '@/components/ui/switch';
import { ModalConfirm, TableReusable, StatsCard } from '@/components/shared';
import type { SpecialOfferIndexProps, SpecialOffer } from '@blog/types';

defineOptions({
    layout: (h: (type: unknown, props: unknown, children: unknown) => VNode, page: VNode) =>
        h(AppLayout, { breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Special Offers', href: '#' },
        ]}, () => page),
});

const props = defineProps<SpecialOfferIndexProps>();

const search = ref(props.filters.search || '');
const isActive = ref(props.filters.is_active || 'all');

const showDeleteModal = ref(false);
const offerToDelete = ref<SpecialOffer | null>(null);
const isDeleting = ref(false);

const columns = [
    { key: 'preview', label: 'Preview', sortable: false },
    { key: 'title', label: 'Title', sortable: true },
    { key: 'subtitle', label: 'Subtitle', sortable: false },
    { key: 'is_active', label: 'Status', sortable: true },
    { key: 'actions', label: '', sortable: false },
];

const actions = [
    {
        label: 'View',
        icon: Eye,
        onClick: (offer: SpecialOffer) => router.visit(`/dashboard/special-offers/${offer.uuid}`),
    },
    {
        label: 'Edit',
        icon: Pencil,
        onClick: (offer: SpecialOffer) => router.visit(`/dashboard/special-offers/${offer.uuid}/edit`),
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        onClick: (offer: SpecialOffer) => confirmDelete(offer),
    },
];

const stats = computed(() => ({
    total: props.stats?.total ?? 0,
    active: props.stats?.active ?? 0,
    inactive: props.stats?.inactive ?? 0,
}));

const applyFilters = () => {
    router.get('/dashboard/special-offers', {
        search: search.value || undefined,
        is_active: isActive.value !== 'all' ? isActive.value : undefined,
    }, { preserveState: true, replace: true });
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
    router.get('/dashboard/special-offers', { ...props.filters, page }, { preserveState: true, replace: true });
};

const handleStatusToggle = (offer: SpecialOffer, newStatus: boolean) => {
    router.put(`/dashboard/special-offers/${offer.uuid}/toggle-active`, { status: newStatus }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const confirmDelete = (offer: SpecialOffer) => {
    offerToDelete.value = offer;
    showDeleteModal.value = true;
};

const handleDelete = () => {
    if (!offerToDelete.value) return;
    isDeleting.value = true;
    router.delete(`/dashboard/special-offers/${offerToDelete.value.uuid}`, {
        onFinish: () => {
            isDeleting.value = false;
            showDeleteModal.value = false;
            offerToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Special Offers" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Special Offers</h1>
                <p class="text-muted-foreground">Manage promotional offers for customer app</p>
            </div>
            <Button @click="router.visit('/dashboard/special-offers/create')" class="gap-2">
                <Plus class="h-4 w-4" />
                Add Offer
            </Button>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <StatsCard title="Total Offers" :value="stats.total" :icon="Tag" variant="info" />
            <StatsCard title="Active" :value="stats.active" :icon="Tag" variant="success" />
            <StatsCard title="Inactive" :value="stats.inactive" :icon="Tag" variant="secondary" />
        </div>

        <Card>
            <CardContent class="p-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <Input v-model="search" placeholder="Search offers..." @keyup.enter="applyFilters" />
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
                    <Button variant="outline" size="sm" @click="clearFilters">Clear</Button>
                </div>
            </CardContent>
        </Card>

        <TableReusable
            :data="specialOffers.data"
            :columns="columns"
            :actions="actions"
            :pagination="specialOffers.meta"
            @page-change="handlePageChange"
            @search="handleSearch"
        >
            <template #cell-preview="{ item }">
                <div
                    class="flex h-10 w-24 items-center justify-center rounded-lg text-white text-xs font-medium"
                    :style="{
                        background: `linear-gradient(135deg, ${item.gradient_start}, ${item.gradient_end})`,
                    }"
                >
                    {{ item.button_text }}
                </div>
            </template>

            <template #cell-title="{ item }">
                <span class="font-medium">{{ item.title }}</span>
            </template>

            <template #cell-subtitle="{ item }">
                <span class="text-sm text-muted-foreground">{{ item.subtitle || '-' }}</span>
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

    <ModalConfirm
        v-model:open="showDeleteModal"
        title="Delete Special Offer"
        :description="`Are you sure you want to delete '${offerToDelete?.title}'? This action cannot be undone.`"
        confirm-text="Delete"
        variant="danger"
        :loading="isDeleting"
        @confirm="handleDelete"
    />
</template>
