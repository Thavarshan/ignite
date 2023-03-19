import { Body, Controller, Get, Post, UseGuards } from '@nestjs/common';
import AuthService from '@/auth/services/auth.service';
import UserContract from '@/auth/contracts/auth-user.contract';
import LoginUserDto from '@/auth/dto/login-user.dto';
import JwtAuthGuard from '@/auth/guards/jwt-auth.guard';
import User from '@/users/decorators/user.decorator';

@Controller()
export default class AuthController {
    constructor (protected readonly service: AuthService) { }

    @Post('login')
    public async login (@Body() dto: LoginUserDto): Promise<any> {
        return await this.service.authenticate(dto);
    }

    @UseGuards(JwtAuthGuard)
    @Get('user')
    public async user (@User() user: UserContract): Promise<UserContract> {
        return user;
    }
}
