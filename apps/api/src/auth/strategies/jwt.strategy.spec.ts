/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import JwtStrategy from './jwt.strategy';
import TokenAuth from '../authenticators/token.auth';
import mockModel from '../../users/models/mock.model';
import JwtPayload from '../contracts/jwt-payload.contract';
import { ConfigModule } from '@nestjs/config';
import authConfig from '../../config/auth.config';

describe('AuthService', () => {
    let auth: TokenAuth;
    let strategy: JwtStrategy;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                JwtStrategy,
                {
                    provide: TokenAuth,
                    useValue: {
                        new: jest.fn().mockResolvedValue(auth),
                        constructor: jest.fn().mockResolvedValue(auth),
                        authenticate: jest.fn(),
                        user: jest.fn()
                    }
                },
                {
                    provide: authConfig.KEY,
                    useValue: {
                        new: jest.fn().mockResolvedValue(authConfig),
                        constructor: jest.fn().mockResolvedValue(authConfig),
                        secretKey: '1234567890'
                    }
                }
            ],
            imports: [ConfigModule]
        }).compile();

        auth = module.get<TokenAuth>(TokenAuth);
        strategy = module.get<JwtStrategy>(JwtStrategy);
    });

    it('should be defined', () => {
        expect(strategy).toBeDefined();
    });

    it('should validate user authentication method', async () => {
        const mockAuth = jest.spyOn(auth, 'authenticate')
            .mockImplementationOnce((payload: any) => {
                return Promise.resolve(undefined) as any;
            });

        const mockUser = jest.spyOn(auth, 'user')
            .mockImplementationOnce(() => {
                return Promise.resolve(mockModel) as any;
            });

        const user = await strategy.validate({ sub: '1234567890' } as JwtPayload);

        expect(user).toEqual(mockModel);
        expect(mockAuth).toBeCalled();
        expect(mockAuth).toHaveBeenCalledWith({
            sub: '1234567890',
        } as JwtPayload);
        expect(mockUser).toBeCalled();
    });
});
