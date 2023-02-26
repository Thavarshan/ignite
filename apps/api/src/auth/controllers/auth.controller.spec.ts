import { Test, TestingModule } from '@nestjs/testing';
import AuthController from './auth.controller';
import AuthService from '../services/auth.service';
import mockModel from '../../users/models/mock.model';

describe('AuthController', () => {
    let service: AuthService;
    let controller: AuthController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [
                AuthController
            ],
            providers: [{
                provide: AuthService,
                useValue: {
                    new: jest.fn(),
                    constructor: jest.fn(),
                    authenticate: jest.fn()
                }
            }]
        }).compile();

        service = module.get<AuthService>(AuthService);
        controller = module.get<AuthController>(AuthController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });

    it('should login a user', async () => {
        jest.spyOn(service, 'authenticate').mockImplementationOnce(() =>
            Promise.resolve({
                accessToken: 'b74842da3b43b37ce12738fcaf3d9b30'
            } as any),
        );

        const credentials = { email: 'ws@wxample.com', password: 'password' };
        const token = await controller.login(credentials);

        expect(token).toStrictEqual({
            accessToken: 'b74842da3b43b37ce12738fcaf3d9b30'
        });
    });

    it('should get currently authenticated user', async () => {
        const request = { user: mockModel };
        const user = await controller.user(request);

        expect(user).toStrictEqual({ user: mockModel });
    });
});
