import { ConfigModule } from '@nestjs/config';
import { Test, TestingModule } from '@nestjs/testing';
import { MailService } from '@sendgrid/mail';
import mailConfig from '../../config/mail.config';
import MailEnum from '../enums/mail.enum';
import mailProviders from './mail.provider';

describe('MailProviders', () => {
    let provider: MailService;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            imports: [ConfigModule.forFeature(mailConfig)],
            providers: [...mailProviders],
        }).compile();

        provider = module.get<MailService>(MailEnum.KEY);
    });

    it('should be defined', () => {
        expect(provider).toBeDefined();
    });
});
