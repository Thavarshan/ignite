import { User } from './user';
import { Thread } from './thread';

export interface Reply {
    id: number;
    body: string;
    user_id: number;
    thread_id: number;
    created_at: string;
    updated_at: string;
    user: User;
    is_best_reply?: boolean;
    thread?: Thread;
}
