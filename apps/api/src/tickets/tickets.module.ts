import { Module } from '@nestjs/common';
import TicketsService from './services/tickets.service';
import ChannelsService from './services/channels.service';
import TicketsController from './controllers/tickets.controller';
import ChannelsController from './controllers/channels.controller';
import ReplyController from './controllers/replies.controller';
import DatabaseModule from '@/database/database.module';
import TicketsRepository from './repositories/tickets.repository';
import RepliesRepository from './repositories/replies.repository';
import RepliesService from './services/replies.service';
import ChannelsRepository from './repositories/channels.repository';
import UsersModule from '@/users/users.module';
import TeamsModule from '@/teams/teams.module';
import ticketsProvider from './providers/tickets.provider';

@Module({
    imports: [
        DatabaseModule,
        UsersModule,
        TeamsModule
    ],

    controllers: [
        ChannelsController,
        TicketsController,
        ReplyController
    ],

    providers: [
        ChannelsRepository,
        TicketsRepository,
        RepliesRepository,
        ChannelsService,
        TicketsService,
        RepliesService,
        ...ticketsProvider
    ],

    exports: [
        ...ticketsProvider
    ]
})
export default class TicketsModule { }
