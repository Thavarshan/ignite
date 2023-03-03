import User from '@/users/models/user.model';
import Channel from '../models/channel.model';
import Reply from '../models/reply.model';
import { Document } from 'mongoose';

export default interface Ticket extends Document {
    title: string;
    body: string;
    slug: string;
    priority?: number;
    locked: boolean;
    type: 'open' | 'closed';
    channel: Partial<Channel>;
    replies: Partial<Reply>;
    user: Partial<User>;
    agent: Partial<User>;
}
