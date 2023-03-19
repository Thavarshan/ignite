import User from '@/users/contracts/user.contract';
import { ConflictException, Injectable, NotFoundException, UnauthorizedException } from '@nestjs/common';
import BaseAuth from './base.auth';
import Credentials from '@/auth/contracts/credentials.contract';
import bcrypt from 'bcrypt';
import Login from '@/auth/enums/login.enum';

@Injectable()
export default class LocalAuth extends BaseAuth {
    /**
     * Authenticate the user using the provided credentials.
     *
     * @param   {Credentials}  credentials
     *
     * @return  {Promise<any>}
     */
    public async authenticate (credentials: Credentials): Promise<any> {
        const user = await this.model
            .findOne({ email: credentials.email, verified: true })
            .populate('team')
            .exec();

        if (!user) {
            throw new NotFoundException('User with these credentials was not found.');
        }

        this.determineUserIdAllowed(user);

        try {
            await this.validate(credentials.password, user.password);

            user.loginAttempts = 0;

            await user.save();

            this.authUser = user;

            return this.authUser;
        } catch (error) {
            user.loginAttempts += 1;

            await user.save();

            if (user.loginAttempts >= Login.LOGIN_ATTEMPTS_TO_BLOCK) {
                await this.blockUser(user);

                throw new ConflictException('User blocked becuase of too many attempts to login.');
            }

            throw new UnauthorizedException(error.message);
        }
    }

    /**
     * Determine if the user is allowed to login to their account.
     *
     * @param   {User}  user
     *
     * @return  {void}
     */
    protected determineUserIdAllowed (user: User): void {
        if (user.blockedAt) {
            throw new ConflictException('User has been blocked from accessing account.');
        }
    }

    /**
     * Validate the user's provided password.
     *
     * @param   {string}         password
     * @param   {string}         hashValue
     *
     * @return  {Promise<void>}
     */
    protected async validate (password: string, hashValue: string): Promise<void> {
        if (!await bcrypt.compare(password, hashValue)) {
            throw new Error('Invalid email or password.');
        }
    }

    /**
     * Block the user's account from being logged into.
     *
     * @param   {User}     user
     *
     * @return  {Promise<void>}
     */
    protected async blockUser (user: User): Promise<void> {
        user.blockedAt = new Date();

        await user.save();
    }
}
