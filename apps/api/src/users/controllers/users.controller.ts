import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import UsersService from '@/users/services/user.service';
import UserDto from '@/users/dto/user.dto';
import User from '@/users/contracts/user.contract';

@Controller('users')
export default class UsersController {
    constructor (protected readonly service: UsersService) { }

    @Post()
    public async create (@Body() dto: UserDto): Promise<User> {
        return this.service.create(dto);
    }

    @Get()
    public async findAll (): Promise<User[]> {
        return await this.service.findAll();
    }

    @Get(':id')
    public async findOne (@Param('id') id: string): Promise<User> {
        return await this.service.findOne(id);
    }

    @Patch(':id')
    public async update (
        @Param('id') id: string,
        @Body() dto: UserDto
    ): Promise<User> {
        return await this.service.update(id, dto);
    }

    @Delete(':id')
    public async remove (@Param('id') id: string): Promise<void> {
        await this.service.remove(id);
    }
}
