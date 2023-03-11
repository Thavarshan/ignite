import { Inject, Injectable, NotFoundException } from '@nestjs/common';
import { Model } from 'mongoose';
import Reply from '../contracts/reply.contract';
import ReplyEnum from '../enums/reply.enum';
import ReplyDto from '../dto/reply.dto';
import User from '@/users/contracts/user.contract';

@Injectable()
export default class RepliesRepository {
    constructor (
        @Inject(ReplyEnum.MODEL) protected model: Model<Reply>
    ) { }

    public async create (dto: ReplyDto): Promise<Reply> {
        return await this.model.create(dto);
    }

    public async findAll (user?: User): Promise<Reply[]> {
        return await this.model
            .find(user ? { user } : null)
            .populate('user')
            .exec();
    }

    public async findById (id: string): Promise<Reply> {
        try {
            return await this.model
                .findById(id)
                .populate('user')
                .exec();
        } catch (error) {
            throw new NotFoundException('Reply not found.');
        }
    }

    public async update (id: string, dto: ReplyDto): Promise<Reply> {
        return await this.model
            .findByIdAndUpdate(id, dto, { new: true })
            .exec();
    }

    public async remove (id: string): Promise<void> {
        await this.model.findByIdAndDelete(id).exec();
    }
}
