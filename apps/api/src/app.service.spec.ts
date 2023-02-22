import { Test, TestingModule } from '@nestjs/testing';
import AppService from './app.service';
import LoggerService from './logger/services/logger.service';
import { Request } from 'express';

describe('AppService', () => {
    let logger: LoggerService;
    let service: AppService;

    beforeEach(async () => {
        const app: TestingModule = await Test.createTestingModule({
            providers: [
                AppService,
                {
                    provide: 'LoggerServiceAppService',
                    useValue: {
                        constructor: jest.fn(),
                        log: jest.fn()
                    }
                }
            ],
        }).compile();

        service = app.get<AppService>(AppService);
        logger = app.get<LoggerService>('LoggerServiceAppService');
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should log ip of request', async () => {
        jest.spyOn(logger, 'log').mockImplementation((message: string) => {
            console.log(message);
        });

        const result = service.logIp({ ip: '127.0.0.1' } as Request);

        expect(result).toEqual('127.0.0.1');
        expect(logger.log).toBeCalledWith('127.0.0.1');
    });
});
