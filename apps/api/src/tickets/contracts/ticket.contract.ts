import User from '@/users/models/user.model';
import Team from '@/teams/models/team.model';
import Channel from '../models/channel.model';
import TicketEnum from '../enums/ticket.enum';
import { Document } from 'mongoose';

export default interface Ticket extends Document {
    title: string;
    body: string;
    slug: string;
    priority?: TicketEnum.HIGH_PRIORITY | TicketEnum.MEDIUM_PRIORITY | TicketEnum.LOW_PRIORITY;
    locked: boolean;
    type: TicketEnum.OPEN | TicketEnum.CLOSED;
    channel: Partial<Channel>;
    user: Partial<User>;
    team: Partial<Team>;
    agent: Partial<User>;
}
