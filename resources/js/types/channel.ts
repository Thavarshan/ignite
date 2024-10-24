export interface Channel {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
    threads_count?: number;
    created_at: string;
    updated_at: string;
}
