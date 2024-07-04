import { User } from '@/types/user';
import { Category } from '@/types/channel';

export interface Note<T = unknown> {
    id: number;
    title: string;
    body: string;
    user_id: number;
    user: User;
    user_name: string;
    notable_id: number;
    notable_type: string;
    notable: T;
    categories: Category[];
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
}

export interface NoteCreate {
    title: string;
    body: string;
    tags?: number[];
}
