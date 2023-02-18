import { Test, TestingModule } from '@nestjs/testing';
import UserRepository from './user.repository';
import { Model } from 'mongoose';
import User from '../contracts/user.contract';
import mockModel from '../models/mock.model';
import { getModelToken } from '@nestjs/mongoose';

describe('UserRepository', () => {
    let repository: UserRepository;
    let model: Model<User>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                UserRepository,
                {
                    provide: getModelToken('User'),
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                        find: jest.fn(),
                        create: jest.fn(),
                        exec: jest.fn(),
                    }
                }
            ],
        }).compile();

        repository = module.get<UserRepository>(UserRepository);
        model = module.get<Model<User>>(getModelToken('User'));
    });

    it('should be defined', () => {
        expect(repository).toBeDefined();
    });

    it('should create a new user', async () => {
        jest.spyOn(model, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                name: 'Wade Solman',
                email: 'ws@wxample.com',
                password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
                phone: '1234567890'
            }),
        );

        const user = await repository.create({
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890'
        });

        expect(user).toEqual(mockModel);
    });
});
