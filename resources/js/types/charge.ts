import { Account } from '@/types/account';
import { Charge as ChargeType } from '@/enums/charge';
import { Payment } from '@/types/payment';

export interface Charge {
    id: number;
    uid: string;
    account_id: number;
    type: ChargeType;
    amount: number; // In cents e.g. 1000 for £10.00
    presentable_amount: string; // In pounds e.g. £10.00
    description?: string;
    metadata?: ChargeMetadata | Record<string, unknown>;
    confirmed_at?: string;
    cancelled_at?: string;
    refunded_at?: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    acount: Account;
    payment?: Payment;
}

export type ChargeMetadata = {
    amount?: number; // In cents e.g. 1000 for £10.00
    service_amount?: number; // In cents e.g. 1000 for £10.00
    discount?: number; // Percentage e.g. 0.1 for 10%
    tax?: number; // Percentage e.g. 0.2 for 20%
    currency?: string;
    priceable_name?: string;
    priceable_type?: string;
    priceable_id?: number;
    price_id?: number;
};
