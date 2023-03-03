import { Injectable } from '@nestjs/common';
import Ticket from '../contracts/ticket.contract';
import TicketDto from '../dto/ticket.dto';
import TicketsRepository from '../repositories/tickets.repository';

@Injectable()
export default class TicketsService {
    constructor (protected readonly repository: TicketsRepository) { }

    public async create (dto: TicketDto): Promise<Ticket> {
        return await this.repository.create(dto);
    }

    public async findAll (): Promise<Ticket[]> {
        return await this.repository.findAll();
    }

    public async findOne (id: string): Promise<Ticket> {
        return await this.repository.findById(id);
    }

    public async update (id: string, dto: TicketDto): Promise<any> {
        return await this.repository.update(id, dto);
    }

    public async remove (id: string): Promise<void> {
        await this.repository.remove(id);
    }
}
