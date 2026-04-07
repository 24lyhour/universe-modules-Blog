import { z } from 'zod';

export const specialOfferSchema = z.object({
    title: z
        .string({ required_error: 'Title is required' })
        .min(1, 'Title is required')
        .max(255, 'Title must be less than 255 characters'),
    subtitle: z.string().max(255).optional().nullable().or(z.literal('')),
    icon: z.string().max(100).optional().nullable().or(z.literal('')),
    gradient_start: z
        .string()
        .regex(/^#[0-9A-Fa-f]{6}$/, 'Must be a valid hex color (e.g. #3BC472)')
        .default('#3BC472'),
    gradient_end: z
        .string()
        .regex(/^#[0-9A-Fa-f]{6}$/, 'Must be a valid hex color (e.g. #2A9D5C)')
        .default('#2A9D5C'),
    button_text: z.string().max(50).optional().nullable().or(z.literal('')),
    deeplink: z.string().max(500).optional().nullable().or(z.literal('')),
    is_active: z.boolean().default(true),
    sort_order: z
        .number()
        .int('Sort order must be a whole number')
        .min(0, 'Sort order cannot be negative')
        .default(0),
    start_at: z.string().optional().nullable().or(z.literal('')),
    end_at: z.string().optional().nullable().or(z.literal('')),
});

export type SpecialOfferFormValues = z.infer<typeof specialOfferSchema>;
