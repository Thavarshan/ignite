import { Test, TestingModule } from '@nestjs/testing';
import RegisterController from './register.controller';
import RegisterService from '../services/register.service';
import User from '../../users/contracts/user.contract';
import mockModel from '../../users/models/mock.model';

describe('RegisterController', () => {
    let service: RegisterService;
    let controller: RegisterController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [RegisterController],
            providers: [{
                provide: RegisterService,
                useValue: {
                    new: jest.fn(),
                    constructor: jest.fn(),
                    register: jest.fn(),
                }
            }]
        }).compile();

        service = module.get<RegisterService>(RegisterService);
        controller = module.get<RegisterController>(RegisterController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });

    it('should register a new user', async () => {
        jest.spyOn(service, 'register')
            .mockImplementationOnce(() => Promise.resolve(mockModel as any));

        const userData = {
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm'
        };

        const user = await controller.register(userData);

        expect(user).toStrictEqual(mockModel);
    });
});
