export interface Channel {
    id: number;
    name: string;
    slug: string;
    description: string;
    parent_id?: number;
    metadata?: Record<string, unknown>[] | string[];
    types?: string[];
    created_at?: string;
    updated_at?: string;
    parent?: Channel;
}
