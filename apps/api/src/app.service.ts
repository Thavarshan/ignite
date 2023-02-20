import { Injectable } from '@nestjs/common';
import { Logger } from '@/logger/decorators/logger.decorator';
import LoggerService from '@/logger/services/logger.service';
import { Request } from 'express';

@Injectable()
export default class AppService {
    constructor (@Logger('AppService') protected logger: LoggerService) { }

    public logIp (request: Request): void {
        this.logger.log(request.ip);
    }
}
