import { Test, TestingModule } from '@nestjs/testing';
import { Connection, Model } from 'mongoose';
import DB from '../../database/enums/db.enum';
import ticketsProvider from './tickets.provider';
import Ticket from '../models/ticket.model';
import Channel from '../models/channel.model';
import Reply from '../models/reply.model';
import TicketEnum from '../enums/ticket.enum';
import ChannelEnum from '../enums/channel.enum';
import ReplyEnum from '../enums/reply.enum';
import TicketContract from '../contracts/ticket.contract';
import ChannelContract from '../contracts/channel.contract';
import ReplyContract from '../contracts/reply.contract';

describe('DatabaseModelsProviders', () => {
    let connection: any;
    let channelModel: any;
    let ticketModel: any;
    let replyModel: any;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                ...ticketsProvider,
                {
                    provide: DB.KEY,
                    useValue: {
                        model: jest
                            .fn()
                            .mockReturnValue({} as Model<
                                ChannelContract |
                                TicketContract |
                                ReplyContract
                            >),
                    },
                },
            ],
        }).compile();

        connection = module.get<Connection>(DB.KEY);
        channelModel = module.get<Channel>(ChannelEnum.MODEL);
        ticketModel = module.get<Ticket>(TicketEnum.MODEL);
        replyModel = module.get<Reply>(ReplyEnum.MODEL);
    });

    it('DB_CONNECTION should be defined', () => {
        expect(connection).toBeDefined();
    });

    it('CHANNEL_MODEL should be defined', () => {
        expect(channelModel).toBeDefined();
    });

    it('TICKET_MODEL should be defined', () => {
        expect(ticketModel).toBeDefined();
    });

    it('REPLY_MODEL should be defined', () => {
        expect(replyModel).toBeDefined();
    });
});
