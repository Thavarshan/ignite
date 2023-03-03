import { Document } from 'mongoose';
import User from '@/users/models/user.model';
import Ticket from '../models/ticket.model';

export default interface Reply extends Document {
    body: string;
    ticket: Partial<Ticket>;
    user: Partial<User>;
    flagged: boolean;
}
