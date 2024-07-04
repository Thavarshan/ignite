export interface Status {
    name: string;
    slug: string;
    reason?: string | null;
    description?: string | null;
    statusable: object;
    statusable_id: number;
    statusable_type: string;
    created_at?: string;
    updated_at?: string;
}
