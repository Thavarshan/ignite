import User from '@/users/contracts/user.contract';
import { BadRequestException, Injectable, NotFoundException } from '@nestjs/common';
import { addHours } from 'date-fns';
import { v4 } from 'uuid';
import { JwtService } from '@nestjs/jwt';
import UserRepository from '@/users/repositories/user.repository';
import Verification from '@/auth/enums/verification.enum';
import CreateUserDto from '@/auth/dto/create-user.dto';
import LocalAuth from '@/auth/authenticators/local.auth';
import Credentials from '@/auth/contracts/credentials.contract';
import { tap } from '@/utils/helpers';

@Injectable()
export default class AuthService {
    constructor (
        protected auth: LocalAuth,
        protected repository: UserRepository,
        protected jwt: JwtService
    ) { }

    public async authenticate (
        credentials: Credentials
    ): Promise<{ [key: string]: string; }> {
        await this.auth.authenticate(credentials);

        const accessToken = tap(this.auth.user(), (user: User) => {
            return this.jwt.sign({
                username: user.username || user.email,
                sub: user._id
            });
        });

        return { accessToken };
    }

    public async create (dto: CreateUserDto): Promise<User> {
        let user: User;

        try {
            user = await this.repository.findByEmail(dto.email);

            if (user && user.verified === true) {
                throw new BadRequestException('Email must be unique.');
            }
        } catch (error) {
            if (!(error instanceof NotFoundException)) {
                throw error;
            }
        }

        user = await this.repository.create(
            this.setRegistrationInfo(dto)
        );

        return user;
    }

    protected setRegistrationInfo (dto: { [key: string]: any; }): any {
        dto.verification = v4();
        dto.verificationExpires = addHours(
            new Date(),
            Verification.HOURS_TO_VERIFY
        );

        return dto;
    }
}
