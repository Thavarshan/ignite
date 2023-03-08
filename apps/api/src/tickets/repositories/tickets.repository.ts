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
        const user = await this.users.findById(dto.user);

        dto.team = user.team as string;

        return await this.model.create(dto);
    }

    public async findAll (user?: User, populate = true): Promise<Ticket[]> {
        const query = this.model.find(user ? { agent: user.id } : null);

        if (populate) {
            await query.populate('channel')
                .populate('user')
                .populate('agent')
                .populate('replies');
        }

        return await query.exec();
    }

    public async findById (id: string, populate = true): Promise<Ticket> {
        try {
            const query = this.model.findById(id);

            if (populate) {
                await query.populate('channel')
                    .populate('user')
                    .populate('agent')
                    .populate('replies');
            }

            return await query.exec();
        } catch (error) {
            throw new NotFoundException('Ticket not found.');
        }
    }

    public async findBySlug (slug: string, populate = true): Promise<Ticket> {
        const query = this.model.findOne({ slug });

        if (populate) {
            await query.populate('channel')
                .populate('user')
                .populate('agent')
                .populate('replies');
        }

        const ticket = await query.exec();

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
