import { Body, Controller, Get, Post, Request, UseGuards } from '@nestjs/common';
import AuthService from '@/auth/services/auth.service';
import User from '@/auth/contracts/auth-user.contract';
import LoginUserDto from '@/auth/dto/login-user.dto';
import JwtAuthGuard from '@/auth/guards/jwt-auth.guard';

@Controller()
export default class AuthController {
    constructor (protected readonly service: AuthService) { }

    @Post('login')
    public async login (@Body() dto: LoginUserDto): Promise<any> {
        return await this.service.authenticate(dto);
    }

    @UseGuards(JwtAuthGuard)
    @Get('user')
    public user (@Request() request): User {
        return request.user;
    }
}
