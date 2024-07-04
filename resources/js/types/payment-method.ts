import { Account } from '@/types/account';
import { PaymentMethod as PaymentMethodType } from '@/enums/payment-method';
import { Charge } from '@/types/charge';
import { Cheque } from '@/types/cheque';

export interface PaymentMethod {
    id: number;
    name: string;
    slug: string;
    description?: string;
    is_default: boolean;
    is_active?: boolean;
    account_id: number;
    currency: string;
    metadata: Cheque | unknown;
    type: PaymentMethodType;
    locked_at?: string;
    created_at: string;
    updated_at: string;
    deleted_at?: string;
    account: Account;
    charges?: Charge[];
}

export interface PaymentMethodCreateUpdate {
    name: string;
    currency: string;
    type: PaymentMethodType;
    description?: string;
    is_default?: boolean;
    is_active?: boolean;
    locked_at?: string;
    metadata?: Record<string, unknown>;
}
