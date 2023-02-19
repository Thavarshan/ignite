import { Strategy } from 'passport-local';
import { PassportStrategy } from '@nestjs/passport';
import { Injectable } from '@nestjs/common';
import LocalAuth from '@/auth/authenticators/local.auth';
import User from '@/users/contracts/user.contract';

@Injectable()
export default class LocalStrategy extends PassportStrategy(Strategy) {
    constructor (protected readonly auth: LocalAuth) {
        super();
    }

    public async validate (email: string, password: string): Promise<User> {
        await this.auth.authenticate({ email, password });

        return this.auth.user();
    }
}
