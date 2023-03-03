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
        return await this.service.findAll();
    }

    @UseGuards(JwtAuthGuard)
    @Get(':slug')
    public async findOne (@Param('slug') slug: string): Promise<any> {
        return await this.service.findOne(slug);
    }

    @UseGuards(JwtAuthGuard)
    @Patch(':slug')
    public async update (
        @Param('slug') slug: string,
        @Body() dto: ChannelDto
    ): Promise<any> {
        return await this.service.update(slug, dto);
    }

    @HttpCode(HttpStatus.NO_CONTENT)
    @UseGuards(JwtAuthGuard)
    @Delete(':slug')
    public async remove (@Param('slug') slug: string): Promise<any> {
        return await this.service.remove(slug);
    }
}
