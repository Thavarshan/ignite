import User from '@/users/contracts/user.contract';
import UsersRepository from '@/users/repositories/users.repository';
import { makeId } from '@/utils/helpers';
import { Inject, Injectable, NotFoundException } from '@nestjs/common';
import { Model } from 'mongoose';
import PasswordResetRequest from '../contracts/password-reset-request.contract';
import PasswordResetRequestDto from '../dto/password-reset-request.dto';
import PasswordResetDto from '../dto/password.reset.dto';
import PasswordEnum from '../enums/password.enum';

@Injectable()
export default class PasswordService {
    constructor (
        @Inject(PasswordEnum.MODEL) protected password: Model<PasswordResetRequest>,
        protected users: UsersRepository
    ) { }

    public async sendResetRequestLink (
        dto: PasswordResetRequestDto
    ): Promise<PasswordResetRequest> {
        let user: User;

        try {
            user = await this.users.findByEmail(dto.email);
        } catch (error) {
            throw new NotFoundException('An account with the given email could not be found.');
        }

        const request = await this.password.create({
            email: user.email,
            token: makeId(32)
        });

        // await Mail.to(user).send();

        return request;
    }

    public async reset (dto: PasswordResetDto): Promise<void> {
        const user = await this.users.findByEmail(dto.email);

        user.password = dto.password;

        await user.save();
    }
}
