import {
    Controller,
    Get,
    Post,
    Body,
    Patch,
    Param,
    Delete,
    UseGuards,
    HttpCode,
    HttpStatus
} from '@nestjs/common';
import UserService from '@/users/services/user.service';
import UserDto from '@/users/dto/user.dto';
import User from '@/users/contracts/user.contract';
import JwtAuthGuard from '@/auth/guards/jwt-auth.guard';

@Controller('users')
export default class UsersController {
    constructor (protected readonly service: UserService) { }

    @HttpCode(HttpStatus.CREATED)
    @Post()
    public async create (@Body() dto: UserDto): Promise<User> {
        return this.service.create(dto);
    }

    @UseGuards(JwtAuthGuard)
    @Get()
    public async findAll (): Promise<{ [key: string]: User[]; }> {
        const data = await this.service.findAll();

        return { data };
    }

    @UseGuards(JwtAuthGuard)
    @Get(':id')
    public async findOne (@Param('id') id: string): Promise<User> {
        return await this.service.findOne(id);
    }

    @UseGuards(JwtAuthGuard)
    @Patch(':id')
    public async update (
        @Param('id') id: string,
        @Body() dto: UserDto
    ): Promise<User> {
        return await this.service.update(id, dto);
    }

    @HttpCode(HttpStatus.NO_CONTENT)
    @UseGuards(JwtAuthGuard)
    @Delete(':id')
    public async remove (@Param('id') id: string): Promise<void> {
        await this.service.remove(id);
    }
}
