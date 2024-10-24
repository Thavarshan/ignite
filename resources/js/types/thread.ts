import { User } from './user';
import { Reply } from './reply';
import { Channel } from './channel';
import { Status } from '@/enums/Status';
import { Priority } from '@/enums/Priority';

export interface Thread {
    id: number;
    title: string;
    body: string;
    slug: string;
    views?: number;
    replies_count: number;
    last_reply_at: string;
    locked_at?: string | null;
    pinned_at?: string | null;
    flagged_at?: string | null;
    is_ticket?: boolean | null;
    status: Status;
    priority: Priority;
    assigned_to_id?: number | null;
    assigned_to?: User;
    user_id: number;
    created_at: string;
    updated_at: string;
    channel_id: number;
    channel: Channel;
    user?: User;
    replies: Reply[];
}
