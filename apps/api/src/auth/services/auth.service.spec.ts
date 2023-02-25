import { Test, TestingModule } from '@nestjs/testing';
import AuthService from './auth.service';
import LocalAuth from '../authenticators/local.auth';
import UserRepository from '../../users/repositories/user.repository';
import TeamRepository from '../../teams/repositories/team.repository';
import mockModel from '../../users/models/mock.model';
import UserEnum from '../../users/enums/user.enum';
import TeamEnum from '../../teams/enums/team.enum';
import { JwtService } from '@nestjs/jwt';
import User from '../../users/contracts/user.contract';
import Role from '../../users/enums/roles.enum';

describe('AuthService', () => {
    let repository: UserRepository;
    let service: AuthService;
    let auth: LocalAuth;
    let jwt: JwtService;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                AuthService,
                UserRepository,
                TeamRepository,
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
                },
                {
                    provide: UserEnum.MODEL,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                        findOne: jest.fn().mockImplementation(() => ({
                            exec: jest.fn()
                        })),
                    }
                },
                {
                    provide: TeamEnum.MODEL,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                    }
                }
            ],
        }).compile();

        repository = module.get<UserRepository>(UserRepository);
        service = module.get<AuthService>(AuthService);
        auth = module.get<LocalAuth>(LocalAuth);
        jwt = module.get<JwtService>(JwtService);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should register a new user', async () => {
        jest.spyOn(repository, 'create')
            .mockImplementationOnce(() => Promise.resolve({
                name: 'Wade Solman',
                email: 'ws@wxample.com',
                password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
                phone: '1234567890',
                roles: [Role.USER]
            } as User));

        const user = await service.register({
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890',
            role: Role.USER
        });

        expect(user).toStrictEqual({
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890',
            roles: [Role.USER]
        });
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
                roles: [Role.USER]
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
