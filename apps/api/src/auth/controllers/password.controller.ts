import { Body, Controller, HttpStatus, HttpCode, Post } from '@nestjs/common';
import PasswordService from '@/auth/services/password.service';
import PasswordResetRequestDto from '@/auth/dto/password-reset-request.dto';
import PasswordResetDto from '@/auth/dto/password.reset.dto';

@Controller()
export default class PasswordController {
    constructor (protected readonly service: PasswordService) { }

    @HttpCode(HttpStatus.ACCEPTED)
    @Post('password-reset-request')
    public async sendResetRequestLink (
        @Body() dto: PasswordResetRequestDto
    ): Promise<any> {
        await this.service.sendResetRequestLink(dto);
    }

    @HttpCode(HttpStatus.OK)
    @Post('reset-password')
    public async resetPassword (@Body() dto: PasswordResetDto): Promise<any> {
        await this.service.reset(dto);
    }
}
