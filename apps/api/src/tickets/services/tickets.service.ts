import { Injectable } from '@nestjs/common';
import TicketDto from '../dto/ticket.dto';

@Injectable()
export default class TicketsService {
    create (dto: TicketDto) {
        return 'This action adds a new ticket';
    }

    findAll () {
        return `This action returns all tickets`;
    }

    findOne (id: number) {
        return `This action returns a #${id} ticket`;
    }

    update (id: number, dto: TicketDto) {
        return `This action updates a #${id} ticket`;
    }

    remove (id: number) {
        return `This action removes a #${id} ticket`;
    }
}
