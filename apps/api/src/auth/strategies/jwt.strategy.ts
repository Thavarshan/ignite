import JwtPayload from '@/auth/contracts/jwt-payload.contract';
import { Inject, Injectable } from '@nestjs/common';
import { PassportStrategy } from '@nestjs/passport';
import { ExtractJwt, Strategy } from 'passport-jwt';
import User from '@/users/contracts/user.contract';
import TokenAuth from '@/auth/authenticators/token.auth';
import { ConfigType } from '@nestjs/config';
import authConfig from '@/config/auth.config';

@Injectable()
export default class JwtStrategy extends PassportStrategy(Strategy) {
    constructor (
        protected readonly auth: TokenAuth,
        @Inject(authConfig.KEY) config: ConfigType<typeof authConfig>
    ) {
        super({
            jwtFromRequest: ExtractJwt.fromAuthHeaderAsBearerToken(),
            ignoreExpiration: false,
            secretOrKey: config.secretKey,
        });
    }

    public async validate (payload: JwtPayload): Promise<User> {
        await this.auth.authenticate(payload);

        return this.auth.user();
    }
}
