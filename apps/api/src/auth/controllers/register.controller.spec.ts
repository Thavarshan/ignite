import { Test, TestingModule } from '@nestjs/testing';
import RegisterController from './register.controller';
import AuthService from '../services/auth.service';

describe('RegisterController', () => {
    let controller: RegisterController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [RegisterController],
            providers: [{
                provide: AuthService,
                useValue: {
                    new: jest.fn(),
                    constructor: jest.fn(),
                }
            }]
        }).compile();

        controller = module.get<RegisterController>(RegisterController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });
});
