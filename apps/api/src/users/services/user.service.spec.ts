/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import UserService from './user.service';
import UserRepository from '../repositories/user.repository';
import mockModel from '../models/mock.model';
import User from '../contracts/user.contract';

describe('UserService', () => {
    let service: UserService;
    let repository: UserRepository;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                UserService,
                {
                    provide: UserRepository,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                        findById: jest.fn(),
                        create: jest.fn(),
                        findAll: jest.fn(),
                        findByEmail: jest.fn(),
                        findByUsername: jest.fn(),
                        findByVerification: jest.fn(),
                        existsByEmail: jest.fn(),
                        update: jest.fn(),
                        remove: jest.fn(),
                        exec: jest.fn()
                    }
                }
            ],
        }).compile();

        service = module.get<UserService>(UserService);
        repository = module.get<UserRepository>(UserRepository);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should create a new user', async () => {
        jest.spyOn(repository, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                name: 'Wade Solman',
                username: 'WadeSolman',
                email: 'ws@wxample.com',
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
        const user = await service.create(userData);

        expect(user).toStrictEqual(userData);
    });

    it('findOne should return user', async () => {
        jest.spyOn(repository, 'findById')
            .mockImplementationOnce((id: string) => {
                return {
                    name: 'James Silverman',
                    email: 'monster@example.com',
                } as any;
            });

        const user = await service.findOne('James Silverman');

        expect(repository.findById).toBeCalledTimes(1);
        expect(repository.findById).lastCalledWith('James Silverman');
        expect(user).toEqual({
            name: 'James Silverman',
            email: 'monster@example.com',
        });
    });

    it('findAll should return user', async () => {
        jest.spyOn(repository, 'findAll')
            .mockImplementationOnce(() => {
                return [{
                    name: 'James Silverman',
                    email: 'monster@example.com',
                }] as any;
            });

        const user = await service.findAll();

        expect(repository.findAll).toBeCalledTimes(1);
        expect(user).toEqual([{
            name: 'James Silverman',
            email: 'monster@example.com',
        }]);
    });

    it('should update an existing user', async () => {
        const userData = {
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            username: 'WadeSolman',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890'
        };

        jest.spyOn(repository, 'update')
            .mockImplementationOnce((_id: string, dto: any) => {
                return userData as any;
            });

        const user = await repository.update('Wade Solman', userData);

        expect(user).toStrictEqual(userData);
        expect(repository.update).toBeCalledTimes(1);
        expect(repository.update).lastCalledWith('Wade Solman', userData);
    });

    it('should delete an existing user', async () => {
        jest.spyOn(repository, 'remove')
            .mockImplementationOnce((_id: string) => {
                return null as any;
            });

        await repository.remove('Wade Solman');

        expect(repository.remove).toBeCalledTimes(1);
        expect(repository.remove).lastCalledWith('Wade Solman');
    });
});
