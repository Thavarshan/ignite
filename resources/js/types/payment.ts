import { Payment as PaymentType } from '@/enums/payment';
import { Account } from '@/types/account';
import { Charge, ChargeMetadata } from '@/types/charge';
import { PaymentMethod } from '@/types/payment-method';
import { DispatchMethod } from '@/types/dispatch-method';

export interface Payment {
    id: number;
    uid?: string;
    account_id: number;
    charge_id: number;
    payment_method_id?: number;
    description?: string;
    type: PaymentType;
    amount: number;
    presentable_amount: string;
    written_amount: string;
    currency: string;
    metadata: PaymentMetadata;
    confirmed_at?: string;
    cancelled_at?: string;
    refunded_at?: string;
    created_at: string;
    updated_at: string;
    account: Account;
    charge: Charge;
    paymentMethod?: PaymentMethod;
    DispatchMethod?: DispatchMethod;
}

export interface PaymentMetadata extends ChargeMetadata {
    payable_id?: number;
    payable_type?: string;
}
