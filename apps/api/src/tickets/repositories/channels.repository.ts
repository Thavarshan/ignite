import { Inject, Injectable, NotFoundException } from '@nestjs/common';
import { Model } from 'mongoose';
import Channel from '../contracts/channel.contract';
import ChannelEnum from '../enums/channel.enum';
import ChannelDto from '../dto/channel.dto';

@Injectable()
export default class ChannelsRepository {
    constructor (
        @Inject(ChannelEnum.MODEL) protected model: Model<Channel>
    ) { }

    public async create (dto: ChannelDto): Promise<Channel> {
        return await this.model.create(dto);
    }

    public async findAll (): Promise<Channel[]> {
        return await this.model.find().exec();
    }

    public async findById (id: string): Promise<Channel> {
        try {
            const channel = await this.model.findById(id).exec();

            return channel;
        } catch (error) {
            throw new NotFoundException('Channel not found.');
        }
    }

    public async findBySlug (slug: string): Promise<Channel> {
        const channel = await this.model.findOne({ slug }).exec();

        if (!channel) {
            throw new NotFoundException('Channel not found.');
        }

        return channel;
    }

    public async update (id: string, dto: ChannelDto): Promise<Channel> {
        return await this.model
            .findByIdAndUpdate(id, dto, { new: true })
            .exec();
    }

    public async remove (id: string): Promise<void> {
        await this.model.findByIdAndDelete(id).exec();
    }
}
