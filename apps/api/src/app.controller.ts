import { Controller, Get } from '@nestjs/common';
import AppService from './app.service';

@Controller()
export default class AppController {
    constructor (protected readonly service: AppService) { }

    @Get()
    public async getHello (): Promise<string> {
        return this.service.getHello();
    }
}
