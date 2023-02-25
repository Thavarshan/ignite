import { Module } from '@nestjs/common';
import TeamsModule from '@/teams/teams.module';
import UsersModule from '@/users/users.module';
import AppController from '@/app.controller';
import AppService from '@/app.service';
import ConfigModule from '@/config/config.module';
import MailModule from '@/mail/mail.module';
import DatabaseModule from '@/database/database.module';
import AuthModule from '@/auth/auth.module';
import LoggerModule from '@/logger/logger.module';

@Module({
    imports: [
        AuthModule,
        UsersModule,
        TeamsModule,
        DatabaseModule,
        ConfigModule,
        MailModule,
        LoggerModule.forRoot(),
        ConfigModule.forRoot({ isGlobal: true }),
    ],

    controllers: [AppController],

    providers: [AppService],
})
export default class AppModule { }
