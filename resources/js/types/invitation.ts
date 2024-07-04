import { Organisation } from '@/types/organisation';

export interface Invitation {
    id: number;
    email: string;
    role: string;
    organisation_id: number;
    accepted_at?: string;
    created_at?: string;
    updated_at?: string;
    organisation: Organisation;
}
