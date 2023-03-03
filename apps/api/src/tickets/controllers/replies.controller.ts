import JwtAuthGuard from '@/auth/guards/jwt-auth.guard';
import { Controller, Get, Post, Body, Patch, Param, Delete, HttpCode, HttpStatus, UseGuards } from '@nestjs/common';
import ReplyDto from '../dto/reply.dto';
import RepliesService from '../services/replies.service';

@Controller('tickets/replies')
export default class ReplysController {
    constructor (protected readonly service: RepliesService) { }

    @UseGuards(JwtAuthGuard)
    @HttpCode(HttpStatus.CREATED)
    @Post()
    public async create (@Body() dto: ReplyDto): Promise<any> {
        return await this.service.create(dto);
    }

    @UseGuards(JwtAuthGuard)
    @Get()
    public async findAll (): Promise<any> {
        return await this.service.findAll();
    }

    @UseGuards(JwtAuthGuard)
    @Get(':id')
    public async findOne (@Param('id') id: string): Promise<any> {
        return await this.service.findOne(id);
    }

    @UseGuards(JwtAuthGuard)
    @Patch(':id')
    public async update (
        @Param('id') id: string,
        @Body() dto: ReplyDto
    ): Promise<any> {
        return await this.service.update(id, dto);
    }

    @HttpCode(HttpStatus.NO_CONTENT)
    @UseGuards(JwtAuthGuard)
    @Delete(':id')
    public async remove (@Param('id') id: string): Promise<any> {
        return await this.service.remove(id);
    }
}
