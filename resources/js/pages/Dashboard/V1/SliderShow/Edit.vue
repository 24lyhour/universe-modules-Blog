<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useModal } from 'momentum-modal';
import { ModalForm } from '@/components/shared';
import { useFormValidation } from '@/composables/useFormValidation';
import { sliderShowSchema } from '@blog/validation/sliderShowSchema';
import SliderShowForm from '@blog/Components/Dashboard/V1/SliderShowForm.vue';
import type { SliderShowEditProps, SliderShowFormData } from '@blog/types';

const props = defineProps<SliderShowEditProps>();

const { show, close, redirect } = useModal();

const isOpen = computed({
    get: () => show.value,
    set: (val: boolean) => {
        if (!val) { close(); redirect(); }
    },
});

const form = useForm<SliderShowFormData>({
    title: props.sliderShow.title,
    description: props.sliderShow.description || '',
    button_text: props.sliderShow.button_text,
    icon: props.sliderShow.icon,
    gradient_start: props.sliderShow.gradient_start || '#3BC472',
    gradient_end: props.sliderShow.gradient_end || '#2A9D5C',
    deeplink: props.sliderShow.deeplink || '',
    is_active: props.sliderShow.is_active,
    sort_order: props.sliderShow.sort_order,
    start_at: props.sliderShow.start_at ? props.sliderShow.start_at.slice(0, 16) : '',
    end_at: props.sliderShow.end_at ? props.sliderShow.end_at.slice(0, 16) : '',
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
        form.put(`/dashboard/slider-shows/${props.sliderShow.uuid}`, {
            onSuccess: () => { close(); redirect(); },
        });
    });
};

const handleCancel = () => { close(); redirect(); };
</script>

<template>
    <ModalForm
        v-model:open="isOpen"
        title="Edit Slider Show"
        :description="`Update '${sliderShow.title}'`"
        mode="edit"
        size="lg"
        :loading="form.processing"
        :disabled="isFormInvalid"
        submit-text="Update Slide"
        @submit="handleSubmit"
        @cancel="handleCancel"
    >
        <SliderShowForm v-model="form" :errors="form.errors" />
    </ModalForm>
</template>
