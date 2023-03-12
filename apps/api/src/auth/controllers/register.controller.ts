import { Body, Controller, HttpCode, HttpStatus, Post } from '@nestjs/common';
import RegisterUserDto from '@/auth/dto/register-user.dto';
import User from '@/users/contracts/user.contract';
import RegisterService from '@/auth/services/register.service';

@Controller()
export default class RegisterController {
    constructor (protected readonly service: RegisterService) { }

    @HttpCode(HttpStatus.CREATED)
    @Post('register')
    public async register (@Body() dto: RegisterUserDto): Promise<User> {
        return await this.service.register(dto);
    }
}
