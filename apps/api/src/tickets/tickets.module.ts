import { Module } from '@nestjs/common';
import TicketsService from './services/tickets.service';
import ChannelsService from './services/channels.service';
import TicketsController from './controllers/tickets.controller';
import ChannelsController from './controllers/channels.controller';
import ReplyController from './controllers/replies.controller';
import DatabaseModule from '@/database/database.module';
import TicketsRepository from './repositories/tickets.repository';
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
    ]
})
export default class TicketsModule { }
