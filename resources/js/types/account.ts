import { BillingCycle } from '@/enums/billing';
import { Charge } from '@/types/charge';
import { Organisation } from '@/types/organisation';
import { Payment } from '@/types/payment';
import { PaymentMethod } from '@/types/payment-method';
import { DispatchMethod } from '@/types/dispatch-method';

export interface Account {
    id: number;
    name: string;
    email: string;
    uid?: string;
    balance: number; // In cents e.g. 1000 for £10.00
    presentable_balance: string; // In pounds e.g. £10.00
    organisation_id: number;
    locked_at?: string;
    credit_limit?: number;
    billing_cycle: BillingCycle;
    last_billed_date?: string;
    current_usage?: number;
    metadata?: Record<string, unknown>;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    organisation: Organisation;
    charges?: Charge[];
    payments?: Payment[];
    paymentMethods?: PaymentMethod[];
    dispatchMethods?: DispatchMethod[];
}
