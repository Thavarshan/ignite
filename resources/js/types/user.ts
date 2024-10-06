// import type { Role } from '@/types/role';
// import type { Team } from '@/types/team';

export interface User {
    id: number;
    uid: string;
    name: string;
    email: string;
    email_verified_at?: string;
    media_url?: string | null;
    // roles?: Role[];
    // teams?: Team[];
    created_at: string;
    updated_at: string | null;
}
