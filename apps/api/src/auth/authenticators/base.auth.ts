import User from '@/users/contracts/user.contract';
import { Model } from 'mongoose';
import { Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';

@Injectable()
export default abstract class BaseAuth {
    /**
     * The currently authenticated user.
     *
     * @var {User}
     */
    protected authUser?: User;

    /**
     * Create new BaseGuard instance.
     *
     * @param   {Model<User>}  model
     *
     * @return  {void}
     */
    constructor (@InjectModel('User') protected model: Model<User>) { }

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
     * @return  {User|undefined|null}
     */
    public user (): User | undefined {
        return this.authUser;
    }
}
