import { Inject, Injectable } from '@nestjs/common';
import { MailService as Sendgrid, MailDataRequired } from '@sendgrid/mail';
import MailEnum from '../enums/mail.enum';

@Injectable()
export default class MailService {
    constructor (
        @Inject(MailEnum.KEY) protected readonly mail: Sendgrid
    ) { }

    public async send (data: MailDataRequired): Promise<any> {
        return await this.mail.send(data, false);
    }
}
