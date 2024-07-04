import type { Team } from '@/types/team';
import type { UserSettings } from '@/types/settings';
import type { Permission } from '@/types/permission';
import type { Role } from '@/types/role';
import type { Location } from '@/types/location';
import type { Organisation } from '@/types/organisation';

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
    all_teams: Partial<Team>[];
    roles: Role[];
    permissions: Permission[];
    organisation_id: number;
    current_team_id: number;
    current_team: Partial<Team>;
    settings?: UserSettings | null;
    location?: Location<Organisation> | null;
    organisation: Organisation;
}

export interface UserUpdate {
    firstname?: User['firstname'];
    lastname?: User['lastname'];
    email?: User['email'];
    image?: File | null;
}
