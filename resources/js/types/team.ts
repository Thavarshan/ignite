import { User } from '@/types/user';

export interface Team {
    id: number;
    name: string;
    description: string;
    owner_id: number;
    owner: User;
    created_at: string;
    updated_at: string;
    deleted_at: string;
}
