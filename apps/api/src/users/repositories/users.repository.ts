import { Inject, Injectable, NotFoundException } from '@nestjs/common';
import { Model } from 'mongoose';
import UserDto from '@/users/dto/user.dto';
import UserEnum from '@/users/enums/user.enum';
import User from '@/users/contracts/user.contract';
import _ from 'lodash';

@Injectable()
export default class UsersRepository {
    constructor (
        @Inject(UserEnum.MODEL) protected model: Model<User>
    ) { }

    public async create (dto: UserDto): Promise<User> {
        return await this.model.create(dto);
    }

    public async findAll (): Promise<User[]> {
        return await this.model.find().exec();
    }

    public async findById (id: string): Promise<User> {
        try {
            const user = await this.model.findById(id).exec();

            if (_.has(user, 'team')) {
                user.populate('team');
            }

            return user;
        } catch (error) {
            throw new NotFoundException('User not found.');
        }
    }

    public async findByEmail (email: string): Promise<User> {
        const user = await this.model.findOne({ email, verified: true }).exec();

        if (!user) {
            throw new NotFoundException('Invalid email or password.');
        }

        return user;
    }

    public async findByUsername (username: string): Promise<User> {
        const user = await this.model.findOne({ username }).exec();

        if (!user) {
            throw new NotFoundException('User not found.');
        }

        return user;
    }

    public async existsByEmail (email: string): Promise<boolean> {
        const results = await this.model.exists({ email }).exec();

        return !_.isNull(results);
    }

    public async update (id: string, dto: UserDto | any): Promise<User> {
        return await this.model
            .findByIdAndUpdate(id, dto, { new: true })
            .exec();
    }

    public async remove (id: string): Promise<void> {
        await this.model.findByIdAndDelete(id).exec();
    }
}
