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
    let userRepository: UserRepository;
    let teamRepository: TeamRepository;
    let service: AuthService;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                JwtService,
                AuthService,
                LocalAuth,
                TeamRepository,
                UserRepository,
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

        userRepository = module.get<UserRepository>(UserRepository);
        teamRepository = module.get<TeamRepository>(TeamRepository);
        service = module.get<AuthService>(AuthService);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should register a new user', async () => {
        jest.spyOn(userRepository, 'create')
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
});
