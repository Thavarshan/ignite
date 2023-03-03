import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import TicketsService from '../services/tickets.service';
import TicketDto from '../dto/ticket.dto';
import UpdateTicketDto from '../dto/update-ticket.dto';

@Controller('tickets')
export default class TicketsController {
    constructor (private readonly ticketsService: TicketsService) { }

    @Post()
    create (@Body() createTicketDto: TicketDto) {
        return this.ticketsService.create(createTicketDto);
    }

    @Get()
    findAll () {
        return this.ticketsService.findAll();
    }

    @Get(':id')
    findOne (@Param('id') id: string) {
        return this.ticketsService.findOne(+id);
    }

    @Patch(':id')
    update (@Param('id') id: string, @Body() updateTicketDto: UpdateTicketDto) {
        return this.ticketsService.update(+id, updateTicketDto);
    }

    @Delete(':id')
    remove (@Param('id') id: string) {
        return this.ticketsService.remove(+id);
    }
}
