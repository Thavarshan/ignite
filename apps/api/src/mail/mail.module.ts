import { Module } from '@nestjs/common';
import mailProviders from './providers/mail.provider';
import MailService from './services/mail.service';
import { ConfigModule } from '@nestjs/config';
import mailConfig from '../config/mail.config';

@Module({
    imports: [ConfigModule.forFeature(mailConfig)],
    providers: [...mailProviders, MailService],
    exports: [...mailProviders, MailService]
})
export default class MailModule { }
