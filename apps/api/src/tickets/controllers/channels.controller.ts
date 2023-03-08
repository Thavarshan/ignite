import JwtAuthGuard from '@/auth/guards/jwt-auth.guard';
import { Controller, Get, Post, Body, Patch, Param, Delete, HttpCode, HttpStatus, UseGuards } from '@nestjs/common';
import ChannelDto from '../dto/channel.dto';
import ChannelsService from '../services/channels.service';

@Controller('channels')
export default class ChannelsController {
    constructor (protected readonly service: ChannelsService) { }

    @UseGuards(JwtAuthGuard)
    @HttpCode(HttpStatus.CREATED)
    @Post()
    public async create (@Body() dto: ChannelDto): Promise<any> {
        return await this.service.create(dto);
    }

    @UseGuards(JwtAuthGuard)
    @Get()
    public async findAll (): Promise<any> {
        const data = await this.service.findAll();

        return { data };
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
        @Body() dto: ChannelDto
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
