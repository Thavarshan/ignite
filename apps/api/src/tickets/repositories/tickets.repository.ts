import { Inject, Injectable, NotFoundException } from '@nestjs/common';
import { Model } from 'mongoose';
import Ticket from '../contracts/ticket.contract';
import TicketEnum from '../enums/ticket.enum';
import TicketDto from '../dto/ticket.dto';
import UsersRepository from '@/users/repositories/users.repository';
import User from '@/users/contracts/user.contract';

@Injectable()
export default class TicketsRepository {
    constructor (
        @Inject(TicketEnum.MODEL) protected model: Model<Ticket>,
        protected users: UsersRepository
    ) { }

    public async create (dto: TicketDto): Promise<Ticket> {
        const agent = await this.users.findById(dto.agent);

        dto.team = agent.team as string;

        return await this.model.create(dto);
    }

    public async findAll (user?: User): Promise<Ticket[]> {
        return this.model.find(user ? { agent: user.id } : null)
            .populate('channel')
            .populate('user')
            .populate('agent')
            .populate('replies')
            .exec();
    }

    public async findById (id: string): Promise<Ticket> {
        try {
            return await this.model.findById(id)
                .populate('channel')
                .populate('user')
                .populate('agent')
                .populate('replies')
                .exec();
        } catch (error) {
            throw new NotFoundException('Ticket not found.');
        }
    }

    public async findBySlug (slug: string): Promise<Ticket> {
        const ticket = this.model.findOne({ slug })
            .populate('channel')
            .populate('user')
            .populate('agent')
            .populate('replies')
            .exec();

        if (!ticket) {
            throw new NotFoundException('Ticket not found.');
        }

        return ticket;
    }

    public async update (id: string, dto: TicketDto): Promise<Ticket> {
        return await this.model
            .findByIdAndUpdate(id, dto, { new: true })
            .exec();
    }

    public async remove (id: string): Promise<void> {
        await this.model.findByIdAndDelete(id).exec();
    }
}
