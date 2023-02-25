import {
    Controller,
    Get,
    HttpStatus,
    Req,
    HttpCode
} from '@nestjs/common';
import { HealthCheck } from '@nestjs/terminus';
import AppService from './app.service';
import { Request } from 'express';

@Controller()
export default class AppController {
    constructor (protected readonly service: AppService) { }

    /**
     * The first page of the app.
     *
     * @param {Request} request
     *
     * @return {Promise<string>}
     */
    @HttpCode(HttpStatus.OK)
    @Get()
    @HealthCheck()
    public async index (@Req() request: Request): Promise<any> {
        return await this.service.check(request);
    }
}
