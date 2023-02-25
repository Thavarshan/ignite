import { Module } from '@nestjs/common';
import TeamService from './services/team.service';
import TeamsController from './controllers/teams.controller';
import UsersModule from '@/users/users.module';
import DatabaseModule from '@/database/database.module';
import TeamRepository from './repositories/team.repository';
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
        TeamService,
        TeamRepository,
        ...TeamsProvider
    ],

    exports: [TeamRepository, ...TeamsProvider]
})
export default class TeamsModule { }
