import type { User } from './auth';

export type Channel = {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    threads_count?: number;
    created_at: string;
    updated_at: string;
};

export type Thread = {
    id: number;
    channel_id: number;
    user_id: number;
    title: string;
    slug: string;
    body: string;
    locked: boolean;
    pinned: boolean;
    replies_count: number;
    best_reply_id: number | null;
    likes_count: number;
    visits: number;
    last_posted_at: string | null;
    created_at: string;
    updated_at: string;
    channel?: Pick<Channel, 'id' | 'name' | 'slug'>;
    user?: Pick<User, 'id' | 'name'>;
    best_reply?: Reply;
};

export type Reply = {
    id: number;
    thread_id: number;
    user_id: number;
    parent_id: number | null;
    body: string;
    likes_count: number;
    is_liked?: boolean;
    created_at: string;
    updated_at: string;
    user?: Pick<User, 'id' | 'name'>;
    children?: Reply[];
};

export type Flag = {
    id: number;
    user_id: number;
    flaggable_id: number;
    flaggable_type: string;
    reason: string;
    description: string | null;
    resolved_at: string | null;
    resolved_by: number | null;
    created_at: string;
    user?: Pick<User, 'id' | 'name'>;
    flaggable?: Thread | Reply;
};

export type AppNotification = {
    id: string;
    type: string;
    data: Record<string, unknown>;
    read_at: string | null;
    created_at: string;
};

export type PaginationLinks = {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
};

export type PaginationMeta = {
    current_page: number;
    from: number | null;
    last_page: number;
    path: string;
    per_page: number;
    to: number | null;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
};

export type PaginatedResponse<T> = {
    data: T[];
    links: PaginationLinks;
    meta: PaginationMeta;
};
