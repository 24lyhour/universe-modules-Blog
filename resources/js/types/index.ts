// ==================== BANNER TYPES ====================

export interface Banner {
    id: number;
    uuid: string;
    name: string;
    description: string | null;
    image_url: string[] | null;
    is_active: boolean;
    start_at: string | null;
    end_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface BannerFormData {
    name: string;
    description: string;
    image_url: string[];
    is_active: boolean;
    start_at: string;
    end_at: string;
}

export interface BannerStats {
    total: number;
    active: number;
    inactive: number;
}

// Pagination
export interface PaginationMeta {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    meta: PaginationMeta;
}

// Props
export interface BannerIndexProps {
    bannerItems: PaginatedResponse<Banner>;
    filters: {
        search?: string;
        is_active?: string;
    };
    stats: BannerStats;
}

export interface BannerCreateProps {
}

export interface BannerEditProps {
    banner: Banner;
}

// ==================== SPECIAL OFFER TYPES ====================

export interface SpecialOffer {
    id: number;
    uuid: string;
    title: string;
    subtitle: string | null;
    icon: string;
    gradient_start: string;
    gradient_end: string;
    button_text: string;
    deeplink: string | null;
    is_active: boolean;
    sort_order: number;
    start_at: string | null;
    end_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface SpecialOfferFormData {
    title: string;
    subtitle: string;
    icon: string;
    gradient_start: string;
    gradient_end: string;
    button_text: string;
    deeplink: string;
    is_active: boolean;
    sort_order: number;
    start_at: string;
    end_at: string;
}

export interface SpecialOfferStats {
    total: number;
    active: number;
    inactive: number;
}

export interface SpecialOfferIndexProps {
    specialOffers: PaginatedResponse<SpecialOffer>;
    filters: {
        search?: string;
        is_active?: string;
    };
    stats: SpecialOfferStats;
}

export interface SpecialOfferCreateProps {
}

export interface SpecialOfferEditProps {
    specialOffer: SpecialOffer;
}

// ==================== SLIDER SHOW TYPES ====================

export interface SliderShow {
    id: number;
    uuid: string;
    title: string;
    description: string | null;
    button_text: string;
    icon: string;
    gradient_start: string;
    gradient_end: string;
    deeplink: string | null;
    is_active: boolean;
    sort_order: number;
    start_at: string | null;
    end_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface SliderShowFormData {
    title: string;
    description: string;
    button_text: string;
    icon: string;
    gradient_start: string;
    gradient_end: string;
    deeplink: string;
    is_active: boolean;
    sort_order: number;
    start_at: string;
    end_at: string;
}

export interface SliderShowStats {
    total: number;
    active: number;
    inactive: number;
}

export interface SliderShowIndexProps {
    sliderShows: PaginatedResponse<SliderShow>;
    filters: {
        search?: string;
        is_active?: string;
    };
    stats: SliderShowStats;
}

export interface SliderShowCreateProps {
}

export interface SliderShowEditProps {
    sliderShow: SliderShow;
}
