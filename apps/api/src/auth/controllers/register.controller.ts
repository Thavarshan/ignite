import { Body, Controller, HttpCode, HttpStatus, Post } from '@nestjs/common';
import CreateUserDto from '@/auth/dto/create-user.dto';
import User from '@/users/contracts/user.contract';
import AuthService from '@/auth/services/auth.service';

@Controller()
export default class RegisterController {
    constructor (protected readonly service: AuthService) { }

    @Post('register')
    @HttpCode(HttpStatus.CREATED)
    public async register (@Body() dto: CreateUserDto): Promise<User> {
        return await this.service.create(dto);
    }
}
