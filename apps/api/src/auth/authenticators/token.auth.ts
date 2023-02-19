import { Injectable, UnauthorizedException } from '@nestjs/common';
import JwtPayload from '@/auth/contracts/jwt-payload.contract';
import BaseAuth from './base.auth';

@Injectable()
export default class TokenAuth extends BaseAuth {
    /**
     * Authenticate the user using the provided credentials.
     *
     * @param   {JwtPayload}  credentials
     *
     * @return  {Promise<any>}
     */
    public async authenticate (credentials: JwtPayload): Promise<any> {
        const user = await this.model.findOne({
            _id: credentials.sub,
            verified: true
        });

        if (!user) {
            throw new UnauthorizedException('Invalid credentials.');
        }

        this.authUser = user;

        return this.authUser;
    }
}
