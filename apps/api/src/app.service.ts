import { Injectable } from '@nestjs/common';
import { Logger } from '@/logger/decorators/logger.decorator';
import LoggerService from '@/logger/services/logger.service';
import { Request } from 'express';

@Injectable()
export default class AppService {
    /**
     * Create new AppService instance.
     *
     * @param {LoggerService} logger
     *
     *
     * @return {void}
     */
    constructor (@Logger('AppService') protected logger: LoggerService) { }

    /**
     * Log request IP address.
     *
     * @param   {Request}  request
     *
     * @return  {string}
     */
    public logIp (request: Request): string {
        this.logger.log(request.ip);

        return request.ip;
    }
}
