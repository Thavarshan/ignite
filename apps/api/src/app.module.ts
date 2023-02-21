import { Module } from '@nestjs/common';
import UsersModule from '@/users/users.module';
import AppController from '@/app.controller';
import AppService from '@/app.service';
import { MongooseModule } from '@nestjs/mongoose';
import { User } from './user/user';
import ConfigModule from '@/config/config.module';
import MailModule from '@/mail/mail.module';
import DatabaseModule from '@/database/database.module';
import AuthModule from '@/auth/auth.module';
import LoggerModule from '@/logger/logger.module';

@Module({
    imports: [
        ConfigModule.forRoot({ isGlobal: true }),
        MongooseModule.forRoot(process.env.DB_URI, { useNewUrlParser: true }),
        AuthModule,
        UsersModule,
        LoggerModule.forRoot(),
        DatabaseModule,
        ConfigModule,
        MailModule,
    ],

    controllers: [AppController],

    providers: [AppService, User],
})
export default class AppModule { }
