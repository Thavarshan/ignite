import { Body, Controller, Post } from '@nestjs/common';
import AuthService from '@/auth/services/auth.service';
import CreateUserDto from '@/auth/dto/create-user.dto';
import User from '@/users/contracts/user.contract';

@Controller('auth')
export default class AuthController {
    constructor (protected readonly service: AuthService) { }

    @Post('register')
    public async create (@Body() dto: CreateUserDto): Promise<User> {
        return await this.service.create(dto);
    }
}
