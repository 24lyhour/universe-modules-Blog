<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useModal } from 'momentum-modal';
import { ModalForm } from '@/components/shared';
import { useFormValidation } from '@/composables/useFormValidation';
import { specialOfferSchema } from '@blog/validation/specialOfferSchema';
import SpecialOfferForm from '@blog/Components/Dashboard/V1/SpecialOfferForm.vue';
import type { SpecialOfferEditProps, SpecialOfferFormData } from '@blog/types';

const props = defineProps<SpecialOfferEditProps>();

const { show, close, redirect } = useModal();

const isOpen = computed({
    get: () => show.value,
    set: (val: boolean) => {
        if (!val) { close(); redirect(); }
    },
});

const form = useForm<SpecialOfferFormData>({
    title: props.specialOffer.title,
    subtitle: props.specialOffer.subtitle || '',
    icon: props.specialOffer.icon,
    gradient_start: props.specialOffer.gradient_start,
    gradient_end: props.specialOffer.gradient_end,
    button_text: props.specialOffer.button_text,
    deeplink: props.specialOffer.deeplink || '',
    is_active: props.specialOffer.is_active,
    sort_order: props.specialOffer.sort_order,
    start_at: props.specialOffer.start_at ? props.specialOffer.start_at.slice(0, 16) : '',
    end_at: props.specialOffer.end_at ? props.specialOffer.end_at.slice(0, 16) : '',
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
        form.put(`/dashboard/special-offers/${props.specialOffer.uuid}`, {
            onSuccess: () => { close(); redirect(); },
        });
    });
};

const handleCancel = () => { close(); redirect(); };
</script>

<template>
    <ModalForm
        v-model:open="isOpen"
        title="Edit Special Offer"
        :description="`Update '${specialOffer.title}'`"
        mode="edit"
        size="lg"
        :loading="form.processing"
        :disabled="isFormInvalid"
        submit-text="Update Offer"
        @submit="handleSubmit"
        @cancel="handleCancel"
    >
        <SpecialOfferForm v-model="form" :errors="form.errors" />
    </ModalForm>
</template>
