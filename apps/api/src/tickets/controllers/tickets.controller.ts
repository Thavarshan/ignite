import { Controller, Get, Post, Body, Patch, Param, Delete, HttpCode, HttpStatus, UseGuards, Req } from '@nestjs/common';
import TicketsService from '../services/tickets.service';
import TicketDto from '../dto/ticket.dto';
import Ticket from '../contracts/ticket.contract';
import JwtAuthGuard from '@/auth/guards/jwt-auth.guard';

@Controller('tickets')
export default class TicketsController {
    constructor (protected readonly service: TicketsService) { }

    @UseGuards(JwtAuthGuard)
    @HttpCode(HttpStatus.CREATED)
    @Post()
    public async create (@Body() dto: TicketDto): Promise<Ticket> {
        return await this.service.create(dto);
    }

    // @UseGuards(JwtAuthGuard)
    @Get()
    public async findAll (@Req() request): Promise<{ data: Ticket[]; }> {
        const data = await this.service.findAll(request.user);

        return { data };
    }

    // @UseGuards(JwtAuthGuard)
    @Get(':id')
    public async findOne (@Param('id') id: string): Promise<Ticket> {
        return await this.service.findOne(id);
    }

    @UseGuards(JwtAuthGuard)
    @Patch(':id')
    public async update (@Param('id') id: string, @Body() dto: TicketDto): Promise<Ticket> {
        return await this.service.update(id, dto);
    }

    @UseGuards(JwtAuthGuard)
    @Delete(':id')
    public async remove (@Param('id') id: string): Promise<void> {
        return await this.service.remove(id);
    }
}
