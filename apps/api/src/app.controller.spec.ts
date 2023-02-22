import { Test, TestingModule } from '@nestjs/testing';
import AppController from './app.controller';
import AppService from './app.service';
import { Request } from 'express';

describe('AppController', () => {
    let controller: AppController;

    beforeEach(async () => {
        const app: TestingModule = await Test.createTestingModule({
            controllers: [AppController],
            providers: [AppService, {
                provide: 'LoggerServiceAppService',
                useValue: {
                    log: jest.fn()
                }
            }],
        }).compile();

        controller = app.get<AppController>(AppController);
    });

    describe('root', () => {
        it('should return request IP', async () => {
            const request = { ip: '127.0.0.1' } as Request;

            expect(await controller.index(request)).toBe('127.0.0.1');
        });
    });
});
