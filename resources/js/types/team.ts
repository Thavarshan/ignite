import type { User } from '@/types/user';
import type { Organisation } from '@/types/organisation';

export interface Team {
    id: number;
    name: string;
    slug: string;
    personal_team: boolean;
    user_id: number; // This is the ID of the owner of the team
    created_at?: string;
    updated_at?: string;
    deleted_at?: string | null;
    owner: User;
    members: User[];
    organisation: Organisation;
}

export interface TeamCreate {
    name: string;
    description?: string;
    image?: File | null;
}
