import { Module } from '@nestjs/common';
import HealthModule from '@/health/health.module';
import TeamsModule from '@/teams/teams.module';
import UsersModule from '@/users/users.module';
import AppController from '@/app.controller';
import AppService from '@/app.service';
import ConfigModule from '@/config/config.module';
import MailModule from '@/mail/mail.module';
import DatabaseModule from '@/database/database.module';
import AuthModule from '@/auth/auth.module';
import LoggerModule from '@/logger/logger.module';
import { TerminusModule } from '@nestjs/terminus';
import TicketsModule from '@/tickets/tickets.module';
import HealthService from '@/health/services/health.service';

@Module({
    imports: [
        AuthModule,
        UsersModule,
        TeamsModule,
        TicketsModule,
        DatabaseModule,
        ConfigModule,
        MailModule,
        HealthModule,
        TerminusModule,
        LoggerModule.forRoot(),
        ConfigModule.forRoot({ isGlobal: true })
    ],

    controllers: [AppController],

    providers: [AppService, HealthService],
})
export default class AppModule { }
