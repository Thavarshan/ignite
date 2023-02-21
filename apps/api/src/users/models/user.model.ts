import Base from '@/database/models/base.model';
import UserContract from '@/users/contracts/user.contract';

export default class User extends Base<UserContract> {
    protected readonly table: string = 'users';
}
