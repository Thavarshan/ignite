import { Test, TestingModule } from '@nestjs/testing';
import AuthService from './auth.service';
import LocalAuth from '../authenticators/local.auth';
import { JwtService } from '@nestjs/jwt';
import User from '../../users/contracts/user.contract';
import Role from '../../users/enums/roles.enum';

describe('AuthService', () => {
    let service: AuthService;
    let auth: LocalAuth;
    let jwt: JwtService;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                AuthService,
                {
                    provide: JwtService,
                    useValue: {
                        new: jest.fn(),
                        constructor: jest.fn(),
                        sign: jest.fn(),
                    }
                },
                {
                    provide: LocalAuth,
                    useValue: {
                        new: jest.fn(),
                        constructor: jest.fn(),
                        authenticate: jest.fn(),
                        user: jest.fn()
                    }
                }
            ],
        }).compile();

        service = module.get<AuthService>(AuthService);
        auth = module.get<LocalAuth>(LocalAuth);
        jwt = module.get<JwtService>(JwtService);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should authenticate a user', async () => {
        jest.spyOn(auth, 'authenticate');
        jest.spyOn(auth, 'user')
            .mockImplementationOnce(() => ({
                name: 'Wade Solman',
                email: 'ws@wxample.com',
                username: 'WadeSolman',
                password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
                phone: '1234567890',
                role: Role.AGENT
            } as User));
        jest.spyOn(jwt, 'sign')
            .mockImplementationOnce(() => 'fake-jwt-token');

        const token = await service.authenticate({
            email: 'ws@wxample.com',
            password: 'password', // password
        });

        expect(token).toStrictEqual({ accessToken: 'fake-jwt-token' });
        expect(auth.authenticate).toBeCalled();
    });
});
