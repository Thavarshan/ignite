import type { Media } from '@/types/media';

export interface Template {
    id: number;
    name: string;
    description: string;
    visibility: boolean;
    templatable_type: string;
    templatable_id: number;
    metadata: Record<string, unknown>;
    created_at?: string;
    updated_at?: string | null;
    deleted_at?: string | null;
    media: Media[];
}

export interface TemplateCreate {
    file: File | null;
    name: string;
    visibility: boolean;
    description?: string;
    metadata?: Record<string, unknown>;
}

export interface TemplateUpdate {
    name: string;
    visibility: boolean;
    description?: string;
    metadata?: Record<string, unknown>;
}
