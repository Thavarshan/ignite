import { Channel } from './channel';
import { Reply } from './reply';
import { User } from './user';

export interface Thread {
    id: number;
    title: string;
    slug: string;
    locked?: boolean;
    locked_at?: string | null;
    body: string;
    author?: User | null;
    channel: Channel;
    replies?: Reply[];
    replies_count?: number | null;
    created_at: string;
    updated_at: string;
}
