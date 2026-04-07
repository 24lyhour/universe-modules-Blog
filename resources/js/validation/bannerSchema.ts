import { z } from 'zod';

export const bannerSchema = z.object({
    name: z
        .string({ required_error: 'Banner name is required' })
        .min(1, 'Banner name is required')
        .max(255, 'Banner name must be less than 255 characters'),
    image_url: z
        .array(z.string().url('Each image must be a valid URL'))
        .min(1, 'At least one image is required'),
    is_active: z.boolean().default(true),
    start_at: z.string().optional().nullable().or(z.literal('')),
    end_at: z.string().optional().nullable().or(z.literal('')),
});

export type BannerFormValues = z.infer<typeof bannerSchema>;
