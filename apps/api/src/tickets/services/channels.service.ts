import { Injectable } from '@nestjs/common';
import Channel from '../contracts/channel.contract';
import ChannelDto from '../dto/channel.dto';
import ChannelsRepository from '../repositories/channels.repository';

@Injectable()
export default class ChannelsService {
    constructor (protected readonly repository: ChannelsRepository) { }

    public async create (dto: ChannelDto): Promise<Channel> {
        return await this.repository.create(dto);
    }

    public async findAll (): Promise<Channel[]> {
        return await this.repository.findAll();
    }

    public async findOne (id: string): Promise<Channel> {
        return await this.repository.findById(id);
    }

    public async update (id: string, dto: ChannelDto): Promise<any> {
        return await this.repository.update(id, dto);
    }

    public async remove (id: string): Promise<void> {
        await this.repository.remove(id);
    }
}
