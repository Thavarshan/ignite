import Base from '@/database/models/base.model';
import UserContract from '@/users/contracts/user.contract';
import { Injectable } from '@nestjs/common';

@Injectable()
export default class User extends Base<UserContract> {
    /**
     * The table associated with the model.
     *
     * @var {string}
     */
    protected readonly table: string = 'users';
}
