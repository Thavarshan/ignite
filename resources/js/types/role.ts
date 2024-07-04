export interface Role {
    id: number;
    name: string;
    slug: string;
    is_default?: boolean;
    description?: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}
