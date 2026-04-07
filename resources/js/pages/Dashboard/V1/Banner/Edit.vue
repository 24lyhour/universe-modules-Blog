<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useModal } from 'momentum-modal';
import { ModalForm } from '@/components/shared';
import { useFormValidation } from '@/composables/useFormValidation';
import { bannerSchema } from '@blog/validation/bannerSchema';
import BannerForm from '@blog/Components/Dashboard/V1/BannerForm.vue';
import type { BannerEditProps, BannerFormData } from '@blog/types';

const props = defineProps<BannerEditProps>();

const { show, close, redirect } = useModal();

const isOpen = computed({
    get: () => show.value,
    set: (val: boolean) => {
        if (!val) { close(); redirect(); }
    },
});

const form = useForm<BannerFormData>({
    name: props.banner.name,
    description: props.banner.description || '',
    image_url: props.banner.image_url || [],
    is_active: props.banner.is_active,
    start_at: props.banner.start_at ? props.banner.start_at.slice(0, 16) : '',
    end_at: props.banner.end_at ? props.banner.end_at.slice(0, 16) : '',
});

const { validateAndSubmit, createIsFormInvalid } = useFormValidation(
    bannerSchema,
    ['name', 'image_url']
);

const getFormData = () => ({
    name: form.name,
    description: form.description,
    image_url: form.image_url,
    is_active: form.is_active,
    start_at: form.start_at,
    end_at: form.end_at,
});

const isFormInvalid = createIsFormInvalid(getFormData);

const handleSubmit = () => {
    validateAndSubmit(getFormData(), form, () => {
        form.put(`/dashboard/banners/${props.banner.uuid}`, {
            onSuccess: () => { close(); redirect(); },
        });
    });
};

const handleCancel = () => { close(); redirect(); };
</script>

<template>
    <ModalForm
        v-model:open="isOpen"
        title="Edit Banner"
        :description="`Update '${banner.name}'`"
        mode="edit"
        size="lg"
        :loading="form.processing"
        :disabled="isFormInvalid"
        submit-text="Update Banner"
        @submit="handleSubmit"
        @cancel="handleCancel"
    >
        <BannerForm v-model="form" :errors="form.errors" />
    </ModalForm>
</template>
