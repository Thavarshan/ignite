import Base from '@/database/models/base.model';
import PasswordContract from '@/auth/contracts/password-reset-request.contract';
import { Injectable } from '@nestjs/common';

@Injectable()
export default class PasswordResetRequest extends Base<PasswordContract> {
    /**
     * The table associated with the model.
     *
     * @var {string}
     */
    protected readonly table: string = 'password_reset_requests';
}
