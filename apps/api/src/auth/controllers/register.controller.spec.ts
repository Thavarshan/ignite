import { Test, TestingModule } from '@nestjs/testing';
import RegisterController from './register.controller';
import AuthService from '../services/auth.service';
import User from '../../users/contracts/user.contract';

describe('RegisterController', () => {
    let service: AuthService;
    let controller: RegisterController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [RegisterController],
            providers: [{
                provide: AuthService,
                useValue: {
                    new: jest.fn(),
                    constructor: jest.fn(),
                    register: jest.fn(),
                }
            }]
        }).compile();

        service = module.get<AuthService>(AuthService);
        controller = module.get<RegisterController>(RegisterController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });

    it('should register a new user', async () => {
        jest.spyOn(service, 'register').mockImplementationOnce(() =>
            Promise.resolve({
                name: 'Wade Solman',
                email: 'ws@wxample.com',
                username: 'WadeSolman',
                password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
                phone: '1234567890'
            } as User),
        );

        const userData = {
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            username: 'WadeSolman',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890'
        };
        const user = await controller.register(userData);

        expect(user).toStrictEqual(userData);
    });
});
