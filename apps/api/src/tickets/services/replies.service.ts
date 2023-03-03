import { Injectable } from '@nestjs/common';
import Reply from '../contracts/reply.contract';
import ReplyDto from '../dto/reply.dto';
import RepliesRepository from '../repositories/replies.repository';

@Injectable()
export default class RepliesService {
    constructor (protected readonly repository: RepliesRepository) { }

    public async create (dto: ReplyDto): Promise<Reply> {
        return await this.repository.create(dto);
    }

    public async findAll (): Promise<Reply[]> {
        return await this.repository.findAll();
    }

    public async findOne (id: string): Promise<Reply> {
        return await this.repository.findById(id);
    }

    public async update (id: string, dto: ReplyDto): Promise<any> {
        return await this.repository.update(id, dto);
    }

    public async remove (id: string): Promise<void> {
        await this.repository.remove(id);
    }
}
