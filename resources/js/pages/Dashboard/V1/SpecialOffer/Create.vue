<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useModal } from 'momentum-modal';
import { ModalForm } from '@/components/shared';
import { useFormValidation } from '@/composables/useFormValidation';
import { specialOfferSchema } from '@blog/validation/specialOfferSchema';
import SpecialOfferForm from '@blog/Components/Dashboard/V1/SpecialOfferForm.vue';
import type { SpecialOfferCreateProps, SpecialOfferFormData } from '@blog/types';

defineProps<SpecialOfferCreateProps>();

const { show, close, redirect } = useModal();

const isOpen = computed({
    get: () => show.value,
    set: (val: boolean) => {
        if (!val) { close(); redirect(); }
    },
});

const form = useForm<SpecialOfferFormData>({
    title: '',
    subtitle: '',
    icon: 'local_offer_rounded',
    gradient_start: '#3BC472',
    gradient_end: '#2A9D5C',
    button_text: 'Shop Now',
    deeplink: '',
    is_active: true,
    sort_order: 0,
    start_at: '',
    end_at: '',
});

const { validateAndSubmit, createIsFormInvalid } = useFormValidation(
    specialOfferSchema,
    ['title']
);

const getFormData = () => ({
    title: form.title,
    subtitle: form.subtitle,
    icon: form.icon,
    gradient_start: form.gradient_start,
    gradient_end: form.gradient_end,
    button_text: form.button_text,
    deeplink: form.deeplink,
    is_active: form.is_active,
    sort_order: form.sort_order,
    start_at: form.start_at,
    end_at: form.end_at,
});

const isFormInvalid = createIsFormInvalid(getFormData);

const handleSubmit = () => {
    validateAndSubmit(getFormData(), form, () => {
        form.post('/dashboard/special-offers', {
            onSuccess: () => { close(); redirect(); },
        });
    });
};

const handleCancel = () => { close(); redirect(); };
</script>

<template>
    <ModalForm
        v-model:open="isOpen"
        title="Create Special Offer"
        description="Add a new promotional offer"
        mode="create"
        size="lg"
        :loading="form.processing"
        :disabled="isFormInvalid"
        submit-text="Create Offer"
        @submit="handleSubmit"
        @cancel="handleCancel"
    >
        <SpecialOfferForm v-model="form" :errors="form.errors" />
    </ModalForm>
</template>
