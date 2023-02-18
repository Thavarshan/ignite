import User from '@/users/contracts/user.contract';
import { BadRequestException, Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/mongoose';
import { addHours } from 'date-fns';
import { Model } from 'mongoose';
import { v4 } from 'uuid';
import UserRepository from '@/users/repositories/user.repository';
import Cryptr from 'cryptr';
import Verification from '@/auth/enums/verification.enum';
import CreateUserDto from '../dto/create-user.dto';

@Injectable()
export default class AuthService {
    protected cryptr?: Cryptr;

    constructor (
        @InjectModel('User') protected model: Model<User>,
        protected repository: UserRepository
    ) {
        // this.cryptr = new Cryptr(process.env.APP_KEY);
    }

    public async create (dto: CreateUserDto): Promise<User> {
        await this.isEmailUnique(dto.email);

        const user = await this.repository.create(
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

    protected async isEmailUnique (email: string): Promise<void> {
        const user = await this.model.findOne({ email, verified: true });

        if (user) {
            throw new BadRequestException('Email must be unique.');
        }
    }
}
