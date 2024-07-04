import { Organisation } from '@/types/organisation';
import { User } from '@/types/user';

export interface Settings<T = unknown> {
    id: number;
    subject_id: number;
    subject_type: string;
    subject: T | null;
    items: Record<string, unknown>;
    created_at?: string;
    updated_at?: string;
}

export interface OrganisationSettings extends Settings<Organisation> {
    items: {
        admins: {
            enabled: boolean;
            limit: number;
        };
        management: {
            enabled: boolean;
            limit: number;
        };
        features: {
            ip_authorisation: {
                enabled: boolean;
                ranges: string[];
            };
        };
    };
}

export interface UserSettings extends Settings<User> {
    items: {
        notifications: {
            email: {
                enabled: boolean;
                frequency: string;
            };
            sms: {
                enabled: boolean;
                frequency: string;
            };
            push: {
                enabled: boolean;
                frequency: string;
            };
        };
    };
}
