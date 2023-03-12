import { Module } from '@nestjs/common';
import TeamsService from './services/teams.service';
import TeamsController from './controllers/teams.controller';
import UsersModule from '@/users/users.module';
import DatabaseModule from '@/database/database.module';
import TeamsRepository from './repositories/teams.repository';
import TeamsProvider from './providers/teams.provider';
import MailModule from '@/mail/mail.module';

@Module({
    imports: [
        MailModule,
        UsersModule,
        DatabaseModule,
    ],

    controllers: [TeamsController],

    providers: [
        TeamsService,
        TeamsRepository,
        ...TeamsProvider
    ],

    exports: [
        TeamsService,
        TeamsRepository,
        ...TeamsProvider
    ]
})
export default class TeamsModule { }
