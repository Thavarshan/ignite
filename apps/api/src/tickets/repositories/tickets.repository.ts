import { Inject, Injectable, NotFoundException } from '@nestjs/common';
import { Model } from 'mongoose';
import Ticket from '../contracts/ticket.contract';
import TicketEnum from '../enums/ticket.enum';
import TicketDto from '../dto/ticket.dto';
import UsersRepository from '@/users/repositories/users.repository';

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

    public async findAll (): Promise<Ticket[]> {
        return await this.model.find().exec();
    }

    public async findById (id: string): Promise<Ticket> {
        try {
            const ticket = await this.model.findById(id).exec();

            return ticket;
        } catch (error) {
            throw new NotFoundException('Ticket not found.');
        }
    }

    public async findBySlug (slug: string): Promise<Ticket> {
        const ticket = await this.model.findOne({ slug }).exec();

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
