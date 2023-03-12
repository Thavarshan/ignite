import User from '@/users/contracts/user.contract';
import { Injectable } from '@nestjs/common';
import { JwtService } from '@nestjs/jwt';
import LocalAuth from '@/auth/authenticators/local.auth';
import Credentials from '@/auth/contracts/credentials.contract';
import { tap } from '@/utils/helpers';

@Injectable()
export default class AuthService {
    constructor (protected auth: LocalAuth, protected jwt: JwtService) { }

    public async authenticate (
        credentials: Credentials
    ): Promise<{ [key: string]: string; }> {
        await this.auth.authenticate(credentials);

        const accessToken = tap(this.auth.user(), (user: User) => {
            return this.jwt.sign({
                username: user.email,
                sub: user._id
            });
        });

        return { accessToken };
    }
}
