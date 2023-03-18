import { Test, TestingModule } from '@nestjs/testing';
import RegisterService from './register.service';
import UsersService from '../../users/services/users.service';
import TeamsService from '../../teams/services/teams.service';
import Roles from '../../users/enums/roles.enum';
import CreateUserDto from '../dto/register-user.dto';
import mockModel from '../../users/models/mock.model';
import { BadRequestException, NotFoundException } from '@nestjs/common';

describe('RegisterService', () => {
    let users: UsersService;
    let teams: TeamsService;
    let service: RegisterService;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                RegisterService,
                {
                    provide: UsersService,
                    useValue: {
                        create: jest.fn(),
                        findOne: jest.fn()
                    }
                },
                {
                    provide: TeamsService,
                    useValue: {
                        create: jest.fn(),
                        findOne: jest.fn()
                    }
                }
            ],
        }).compile();

        users = module.get<UsersService>(UsersService);
        teams = module.get<TeamsService>(TeamsService);
        service = module.get<RegisterService>(RegisterService);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should register a new user', async () => {
        jest.spyOn(users, 'findOne')
            .mockImplementation(() => { throw new NotFoundException(); });
        jest.spyOn(users, 'create')
            .mockImplementationOnce(() => Promise.resolve(mockModel as any));
        jest.spyOn(teams, 'findOne')
            .mockImplementationOnce(() => Promise.resolve({ id: 'fake-team-id' } as any));

        const user = await service.register({
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890',
            role: Roles.USER,
            team: 'fake-team-id'
        } as CreateUserDto);

        expect(user).toStrictEqual(mockModel);
    });

    it('should register a new agent', async () => {
        jest.spyOn(users, 'findOne')
            .mockImplementation(() => { throw new NotFoundException(); });
        jest.spyOn(users, 'create')
            .mockImplementationOnce(() => Promise.resolve(mockModel as any));
        jest.spyOn(teams, 'findOne')
            .mockImplementationOnce(() => Promise.resolve({ id: 'fake-team-id' } as any));

        const user = await service.register({
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890',
            role: Roles.AGENT,
            team: 'fake-team-id'
        } as CreateUserDto);

        expect(user).toStrictEqual(mockModel);
        expect(user.team).toStrictEqual('fake-team-id');
    });

    it('should register a new admin', async () => {
        mockModel.role = Roles.ADMIN;
        jest.spyOn(users, 'findOne')
            .mockImplementation(() => { throw new NotFoundException(); });
        jest.spyOn(users, 'create')
            .mockImplementationOnce(() => Promise.resolve(mockModel as any));
        jest.spyOn(teams, 'create')
            .mockImplementationOnce(() => Promise.resolve({ id: 'fake-team-id' } as any));

        const user = await service.register({
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890',
            role: Roles.ADMIN
        } as CreateUserDto);

        expect(user).toStrictEqual(mockModel);
        expect(user.team).toStrictEqual('fake-team-id');
    });

    it('should reject duplicate user', async () => {
        jest.spyOn(users, 'findOne')
            .mockImplementation(() => Promise.resolve(mockModel as any));

        try {
            await service.register({
                name: 'Wade Solman',
                email: 'ws@wxample.com',
                password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
                phone: '1234567890',
                role: Roles.USER,
                team: 'fake-team-id'
            } as CreateUserDto);
        } catch (error) {
            expect(error).toBeInstanceOf(BadRequestException);

            return;
        }

        fail('Duplicate user found exception was not thrown');
    });
});
