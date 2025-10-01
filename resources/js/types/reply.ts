import { Thread } from './thread';
import { User } from './user';

export interface Reply {
    id: number;
    body: string;
    user_id: number;
    thread_id: number;
    author?: User | null;
    thread?: Thread | null;
    flagged?: boolean;
    flagged_at?: string | null;
    created_at: string;
    updated_at: string;
}
