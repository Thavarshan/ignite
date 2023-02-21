import { Injectable, NotFoundException } from '@nestjs/common';
import { Model } from 'mongoose';
import UserDto from '@/users/dto/user.dto';
import UserEnum from '@/users/enums/user.enum';
import { InjectModel } from '@nestjs/mongoose';
import User from '@/users/contracts/user.contract';
import _ from 'lodash';

@Injectable()
export default class UserRepository {
    constructor (
        @InjectModel(UserEnum.MODEL) protected model: Model<User>
    ) { }

    public async create (dto: UserDto): Promise<User> {
        return await this.model.create(dto);
    }

    public async findAll (): Promise<User[]> {
        return await this.model.find().exec();
    }

    public async findOne (id: string): Promise<any> {
        return await this.model.findById(id).exec();
    }

    public async findByEmail (email: string): Promise<User> {
        const user = await this.model.findOne({ email, verified: true }).exec();

        if (!user) {
            throw new NotFoundException('Invalid email or password.');
        }

        return user;
    }

    public async findByVerification (
        verification: string,
        expires?: Date
    ): Promise<User> {
        const user = await this.model.findOne({
            verification,
            verified: false,
            verificationExpires: { $gt: expires || new Date() }
        }).exec();

        if (!user) {
            throw new NotFoundException('User not found.');
        }

        return user;
    }

    public async existsByEmail (email: string): Promise<boolean> {
        const results = await this.model.exists({ email }).exec();

        return !_.isNull(results);
    }

    public async update (id: string, dto: UserDto): Promise<any> {
        return await this.model.findOneAndUpdate({ _id: id }, dto).exec();
    }

    public async remove (id: string): Promise<void> {
        await this.model.deleteOne({ _id: id }).exec();
    }
}
