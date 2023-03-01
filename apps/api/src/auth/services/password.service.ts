import UserRepository from '@/users/repositories/user.repository';
import { makeId } from '@/utils/helpers';
import { Inject, Injectable } from '@nestjs/common';
import { Model } from 'mongoose';
import PasswordResetRequest from '../contracts/password-reset-request.contract';
import PasswordResetRequestDto from '../dto/password-reset-request.dto';
import PasswordResetDto from '../dto/password.reset.dto';
import Password from '../enums/password.enum';

@Injectable()
export default class PasswordService {
    constructor (
        @Inject(Password.MODEL) protected password: Model<PasswordResetRequest>,
        protected users: UserRepository
    ) { }

    public async sendResetRequestLink (
        dto: PasswordResetRequestDto
    ): Promise<any> {
        const request = await this.password.create({
            email: dto.email,
            token: makeId()
        });

        const user = this.users.findByEmail(request.email);

        return user;

        // await Mail.to(user).send();
    }

    public async reset (dto: PasswordResetDto): Promise<void> {
        const user = await this.users.findByEmail(dto.email);

        user.password = dto.password;

        await user.save();
    }
}
