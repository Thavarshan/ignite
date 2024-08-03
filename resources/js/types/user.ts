import type { Team } from '@/types/team';
import { Permission } from '@/types/permission';
import { Role } from '@/types/role';

export interface User {
    id: number;
    name: string;
    firstname: string;
    middlesnames?: string | null;
    lastname: string;
    email: string;
    email_verified_at?: string;
    two_factor_enabled: boolean;
    two_factor_comfirmed_at?: string;
    media_url?: string | null;
    roles: Role[];
    permissions: Permission[];
    all_teams: Partial<Team>[];
    organisation_id: number;
    current_team_id: number;
    current_team: Partial<Team>;
}

export interface UserLogin {
    email: User['email'];
    password: string;
    remember?: boolean;
}

export interface UserCreate {
    firstname: User['firstname'];
    lastname: User['lastname'];
    email: User['email'];
    password: string;
    password_confirmation: string;
    image?: File | null;
}

export interface UserUpdate {
    firstname?: User['firstname'];
    lastname?: User['lastname'];
    email?: User['email'];
    image?: File | null;
}
