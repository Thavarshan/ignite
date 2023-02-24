/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import LocalStrategy from './local.strategy';
import LocalAuth from '../authenticators/local.auth';
import mockModel from '../../users/models/mock.model';

describe('AuthService', () => {
    let auth: LocalAuth;
    let strategy: LocalStrategy;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                LocalStrategy,
                {
                    provide: LocalAuth,
                    useValue: {
                        new: jest.fn().mockResolvedValue(auth),
                        constructor: jest.fn().mockResolvedValue(auth),
                        authenticate: jest.fn(),
                        user: jest.fn()
                    }
                }
            ],
        }).compile();

        auth = module.get<LocalAuth>(LocalAuth);
        strategy = module.get<LocalStrategy>(LocalStrategy);
    });

    it('should be defined', () => {
        expect(strategy).toBeDefined();
    });

    it('should validate user authentication method', async () => {
        const mockAuth = jest.spyOn(auth, 'authenticate')
            .mockImplementationOnce(({ email, verified }: any) => {
                return Promise.resolve(undefined) as any;
            });

        const mockUser = jest.spyOn(auth, 'user')
            .mockImplementationOnce(() => {
                return Promise.resolve(mockModel) as any;
            });

        const user = await strategy.validate('james@example.com', '1234567890');

        expect(user).toEqual(mockModel);
        expect(mockAuth).toBeCalled();
        expect(mockAuth).toHaveBeenCalledWith({
            email: 'james@example.com',
            password: '1234567890'
        });
        expect(mockUser).toBeCalled();
    });
});
