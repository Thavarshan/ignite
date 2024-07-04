import { DispatchMethod as DispatchMethodType } from '@/enums/dispatch-method';
import { Account } from '@/types/account';
import { Report } from '@/types/report';
import { Payment } from '@/types/payment';

export interface DispatchMethod {
    id: number;
    name: string;
    slug: string;
    account_id: number;
    description?: string;
    is_default: boolean;
    type: DispatchMethodType;
    metadata?: EmailLetter | PostLetter | PostForm | Online | Record<string, unknown>;
    locked_at?: string;
    created_at: string;
    updated_at: string;
    account: Account;
    reports?: Report[];
    payments?: Payment[];
}

export interface EmailLetter {
    email: string;
}

export interface PostLetter extends LetterAddress {}

export interface PostForm extends LetterAddress {}

export interface Online {
    url: string;
}

export type LetterAddress = {
    to?: string;
    line1?: string;
    line2?: string;
    line3?: string;
    city?: string;
    state?: string;
    postal_code?: string;
    country?: string;
};
