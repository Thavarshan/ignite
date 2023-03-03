import User from '@/users/contracts/user.contract';
import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common';
import { addHours } from 'date-fns';
import { v4 } from 'uuid';
import { JwtService } from '@nestjs/jwt';
import UsersRepository from '@/users/repositories/users.repository';
import Verification from '@/auth/enums/verification.enum';
import CreateUserDto from '@/auth/dto/create-user.dto';
import LocalAuth from '@/auth/authenticators/local.auth';
import Credentials from '@/auth/contracts/credentials.contract';
import { tap } from '@/utils/helpers';
import TeamsRepository from '@/teams/repositories/teams.repository';
import _ from 'lodash';
import Roles from '@/users/enums/roles.enum';

@Injectable()
export default class AuthService {
    constructor (
        protected auth: LocalAuth,
        protected users: UsersRepository,
        protected teams: TeamsRepository,
        protected jwt: JwtService
    ) { }

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

    public async register (dto: CreateUserDto): Promise<User> {
        let user: User;

        try {
            user = await this.users.findByEmail(dto.email);

            if (user && user.verified === true) {
                throw new BadRequestException('Email already in use.');
            }
        } catch (error) {
            if (!(error instanceof NotFoundException)) {
                throw error;
            }
        }

        user = await this.users.create(this.setRegistrationInfo(dto));

        if (!_.includes(user.roles, Roles.USER)) {
            const team = await this.teams.create({
                name: `${user.name}'s Team`,
                email: user.email,
                owner: _.get(user, '_id')
            });

            user.team = _.get(team, '_id');

            await user.save();
        }

        return user;
    }

    protected setRegistrationInfo (dto: { [key: string]: any; }): any {
        if (_.has(dto, 'role')) {
            dto.roles = [dto.role];
        }

        dto.verified = true;
        dto.verification = v4();
        dto.verificationExpires = addHours(
            new Date(),
            Verification.HOURS_TO_VERIFY
        );

        return dto;
    }
}
