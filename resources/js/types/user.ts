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
    profile_photo_path?: string;
    profile_photo_url?: string;
    two_factor_enabled?: boolean | null;
}

export interface UpdateUser extends Partial<User> {
    _method: 'PUT';
    name: string;
    email: string;
    photo: File | null;
}

export interface UpdatePassword {
    current_password: string;
    password: string;
    password_confirmation: string;
}
