export interface Role {
    id: number;
    name: string;
    slug: RoleSlug;
    is_default?: boolean;
    description?: string;
}

export type RoleSlug = 'admin' | 'staff' | 'client-owner' | 'client-manager' | 'client-staff';
