import { Provider } from '@nestjs/common';
import DB from '@/database/enums/db.enum';
import Connection from '@/database/connection';
import ChannelSchema from '../schemas/channel.schema';
import TicketSchema from '../schemas/ticket.schema';
import ReplySchema from '../schemas/reply.schema';
import ChannelEnum from '../enums/channel.enum';
import TicketEnum from '../enums/ticket.enum';
import ReplyEnum from '../enums/reply.enum';
import Channel from '../models/channel.model';
import Ticket from '../models/ticket.model';
import Reply from '../models/reply.model';

const ticketsProvider: Provider[] = [
    {
        provide: TicketEnum.MODEL,

        useFactory: (connection: Connection) => {
            const ticket = new Ticket(connection, TicketSchema);

            return ticket.newInstance();
        },

        inject: [DB.KEY],
    },
    {
        provide: ChannelEnum.MODEL,

        useFactory: (connection: Connection) => {
            const channel = new Channel(connection, ChannelSchema);

            return channel.newInstance();
        },

        inject: [DB.KEY],
    },
    {
        provide: ReplyEnum.MODEL,

        useFactory: (connection: Connection) => {
            const reply = new Reply(connection, ReplySchema);

            return reply.newInstance();
        },

        inject: [DB.KEY],
    }
];

export default ticketsProvider;
