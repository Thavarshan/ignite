export interface Permission {
    id: number;
    name: PermissionName;
    slug: string;
    created_at?: string;
    updated_at?: string;
}

export type PermissionName = 'police:*' | 'police:read'; // add additional permission names as needed
