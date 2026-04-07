<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useModal } from 'momentum-modal';
import { ModalForm } from '@/components/shared';
import { useFormValidation } from '@/composables/useFormValidation';
import { sliderShowSchema } from '@blog/validation/sliderShowSchema';
import SliderShowForm from '@blog/Components/Dashboard/V1/SliderShowForm.vue';
import type { SliderShowCreateProps, SliderShowFormData } from '@blog/types';

defineProps<SliderShowCreateProps>();

const { show, close, redirect } = useModal();

const isOpen = computed({
    get: () => show.value,
    set: (val: boolean) => {
        if (!val) { close(); redirect(); }
    },
});

const form = useForm<SliderShowFormData>({
    title: '',
    description: '',
    button_text: 'Shop Now',
    icon: 'flash_on',
    gradient_start: '#3BC472',
    gradient_end: '#2A9D5C',
    deeplink: '',
    is_active: true,
    sort_order: 0,
    start_at: '',
    end_at: '',
});

const { validateAndSubmit, createIsFormInvalid } = useFormValidation(
    sliderShowSchema,
    ['title']
);

const getFormData = () => ({
    title: form.title,
    description: form.description,
    button_text: form.button_text,
    icon: form.icon,
    gradient_start: form.gradient_start,
    gradient_end: form.gradient_end,
    deeplink: form.deeplink,
    is_active: form.is_active,
    sort_order: form.sort_order,
    start_at: form.start_at,
    end_at: form.end_at,
});

const isFormInvalid = createIsFormInvalid(getFormData);

const handleSubmit = () => {
    validateAndSubmit(getFormData(), form, () => {
        form.post('/dashboard/slider-shows', {
            onSuccess: () => { close(); redirect(); },
        });
    });
};

const handleCancel = () => { close(); redirect(); };
</script>

<template>
    <ModalForm
        v-model:open="isOpen"
        title="Create Slider Show"
        description="Add a new promo slide"
        mode="create"
        size="lg"
        :loading="form.processing"
        :disabled="isFormInvalid"
        submit-text="Create Slide"
        @submit="handleSubmit"
        @cancel="handleCancel"
    >
        <SliderShowForm v-model="form" :errors="form.errors" />
    </ModalForm>
</template>
