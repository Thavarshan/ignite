import UserContract from '@/users/contracts/user.contract';
import UserEnum from '@/users/enums/user.enum';
import { Model } from 'mongoose';
import { Inject, Injectable } from '@nestjs/common';

@Injectable()
export default abstract class BaseAuth {
    /**
     * The currently authenticated user.
     *
     * @var {User}
     */
    protected authUser?: UserContract;

    /**
     * Create new BaseGuard instance.
     *
     * @param   {Model<UserContract>}  model
     *
     * @return  {void}
     */
    constructor (
        @Inject(UserEnum.MODEL) protected model: Model<UserContract>
    ) { }

    /**
    * Authenticate the user using the provided credentials.
    *
    * @param   {any}  credentials
    *
    * @return  {Promise<any>}
    */
    abstract authenticate (...credentials: any): Promise<any>;

    /**
     * Get the currently authenticated user.
     *
     * @return  {UserContract|undefined|null}
     */
    public user (): UserContract | undefined {
        return this.authUser;
    }
}
