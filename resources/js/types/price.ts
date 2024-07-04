import { Price as PriceEnum } from '@/enums/price';

export interface Price<T = unknown> {
    id: number;
    uid: string;
    type: PriceEnum;
    priceable_id?: number;
    priceable_type?: string;
    priceable?: T; // This is a polymorphic relationship
    amount: number; // In cents e.g. 1000 for £10.00
    presentable_amount: string; // In pounds e.g. £10.00
    service_amount: number;
    presentable_service_amount: string;
    chargeable_amount: number; // This is a computed value of the amount with discount and tax applied, in cents e.g. 1000 for £10.00
    presentable_chargeable_amount: string; // In pounds e.g. £10.00
    currency: string;
    discount?: number; // Percentage as fraction e.g. 0.1 for 10%
    tax?: number; // Percentage as fraction
    description?: string;
    metadata?: Record<string, unknown>;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

export interface PriceCreateUpdate {
    description?: string;
    amount: number;
    type: PriceEnum;
    currency: string;
    discount?: number;
    tax?: number;
    metadata?: Record<string, unknown>;
}
