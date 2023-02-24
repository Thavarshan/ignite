import { ConfigType } from '@nestjs/config';
import { MailService } from '@sendgrid/mail';
import mailConfig from '../../config/mail.config';
import MailEnum from '../enums/mail.enum';

const mailProviders = [
    {
        provide: MailEnum.KEY,
        useFactory: (config: ConfigType<typeof mailConfig>): MailService => {
            const mail = new MailService();

            mail.setApiKey(config.apiKey);
            mail.setTimeout(5000);

            return mail;
        },
        inject: [mailConfig.KEY],
    }
];

export default mailProviders;
