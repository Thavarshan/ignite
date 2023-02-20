import { Controller, Get, HttpStatus, Req, HttpCode } from '@nestjs/common';
import AppService from './app.service';
import { Request } from 'express';

@Controller()
export default class AppController {
    constructor (protected readonly service: AppService) { }

    @HttpCode(HttpStatus.OK)
    @Get()
    public async index (@Req() request: Request): Promise<void> {
        this.service.logIp(request);
    }
}
