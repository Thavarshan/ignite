import { Inject, Injectable, NotFoundException } from '@nestjs/common';
import { Model } from 'mongoose';
import Reply from '../contracts/reply.contract';
import ReplyEnum from '../enums/reply.enum';
import ReplyDto from '../dto/reply.dto';

@Injectable()
export default class RepliesRepository {
    constructor (
        @Inject(ReplyEnum.MODEL) protected model: Model<Reply>
    ) { }

    public async create (dto: ReplyDto): Promise<Reply> {
        return await this.model.create(dto);
    }

    public async findAll (): Promise<Reply[]> {
        return await this.model.find().exec();
    }

    public async findById (id: string): Promise<Reply> {
        try {
            const reply = await this.model.findById(id).exec();

            return reply;
        } catch (error) {
            throw new NotFoundException('Reply not found.');
        }
    }

    public async findBySlug (slug: string): Promise<Reply> {
        const reply = await this.model.findOne({ slug }).exec();

        if (!reply) {
            throw new NotFoundException('Reply not found.');
        }

        return reply;
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
