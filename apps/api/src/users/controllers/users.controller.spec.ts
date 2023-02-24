/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import UsersController from './users.controller';
import UserService from '../services/user.service';
import mockModel from '../models/mock.model';
import User from '../contracts/user.contract';

describe('UsersController', () => {
    let service: UserService;
    let controller: UsersController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [UsersController],
            providers: [
                {
                    provide: UserService,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                        findOne: jest.fn(),
                        findAll: jest.fn(),
                        create: jest.fn(),
                        update: jest.fn(),
                        remove: jest.fn(),
                    }
                }
            ],
        }).compile();

        service = module.get<UserService>(UserService);
        controller = module.get<UsersController>(UsersController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });

    it('should create a new user', async () => {
        jest.spyOn(service, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                name: 'Wade Solman',
                email: 'ws@wxample.com',
                password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
                phone: '1234567890'
            } as User),
        );

        const userData = {
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890'
        };
        const user = await controller.create(userData);

        expect(user).toStrictEqual(userData);
    });

    it('findOne should return user', async () => {
        jest.spyOn(service, 'findOne')
            .mockImplementationOnce((id: string) => {
                return {
                    name: 'James Silverman',
                    email: 'monster@example.com',
                } as any;
            });

        const user = await service.findOne('James Silverman');

        expect(service.findOne).toBeCalledTimes(1);
        expect(service.findOne).lastCalledWith('James Silverman');
        expect(user).toEqual({
            name: 'James Silverman',
            email: 'monster@example.com',
        });
    });

    it('findAll should return user', async () => {
        jest.spyOn(service, 'findAll')
            .mockImplementationOnce(() => {
                return [{
                    name: 'James Silverman',
                    email: 'monster@example.com',
                }] as any;
            });

        const user = await service.findAll();

        expect(service.findAll).toBeCalledTimes(1);
        expect(user).toEqual([{
            name: 'James Silverman',
            email: 'monster@example.com',
        }]);
    });

    it('should update an existing user', async () => {
        const userData = {
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890'
        };

        jest.spyOn(service, 'update')
            .mockImplementationOnce((_id: string, dto: any) => {
                return userData as any;
            });

        const user = await service.update('Wade Solman', userData);

        expect(user).toStrictEqual(userData);
        expect(service.update).toBeCalledTimes(1);
        expect(service.update).lastCalledWith('Wade Solman', userData);
    });

    it('should delete an existing user', async () => {
        jest.spyOn(service, 'remove')
            .mockImplementationOnce((_id: string) => {
                return null as any;
            });

        await service.remove('Wade Solman');

        expect(service.remove).toBeCalledTimes(1);
        expect(service.remove).lastCalledWith('Wade Solman');
    });
});
