import JwtPayload from '@/auth/contracts/jwt-payload.contract';
import { Injectable } from '@nestjs/common';
import { PassportStrategy } from '@nestjs/passport';
import { ExtractJwt, Strategy } from 'passport-jwt';
import User from '@/users/contracts/user.contract';
import TokenAuth from '@/auth/authenticators/token.auth';

@Injectable()
export default class JwtStrategy extends PassportStrategy(Strategy) {
    constructor (protected readonly auth: TokenAuth) {
        super({
            jwtFromRequest: ExtractJwt.fromAuthHeaderAsBearerToken(),
            ignoreExpiration: false,
            secretOrKey: '38b060a751ac96384cd9327eb1b1e',
        });
    }

    public async validate (payload: JwtPayload): Promise<User> {
        await this.auth.authenticate(payload);

        return this.auth.user();
    }
}
