/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import UserRepository from './user.repository';
import { Model } from 'mongoose';
import User from '../contracts/user.contract';
import UserEnum from '../enums/user.enum';
import mockModel from '../models/mock.model';

describe('UserRepository', () => {
    let repository: UserRepository;
    let model: Model<User>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                UserRepository,
                {
                    provide: UserEnum.MODEL,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                        findOne: jest.fn(),
                        findById: jest.fn(),
                        create: jest.fn(),
                        findOneAndUpdate: jest.fn(),
                        deleteOne: jest.fn(),
                        exists: jest.fn(),
                        exec: jest.fn(),
                    }
                }
            ],
        }).compile();

        repository = module.get<UserRepository>(UserRepository);
        model = module.get<Model<User>>(UserEnum.MODEL);
    });

    it('should be defined', () => {
        expect(repository).toBeDefined();
    });

    it('should create a new user', async () => {
        jest.spyOn(model, 'create').mockImplementationOnce(() =>
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
            username: 'WadeSolman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890'
        };
        const user = await repository.create(userData);

        expect(user).toStrictEqual(userData);
    });

    it('findByEmail should return user', async () => {
        jest.spyOn(model, 'findOne')
            .mockImplementation((filter: any, callback: any) => {
                return {
                    exec: jest.fn().mockResolvedValue({
                        name: 'James Silverman',
                        email: 'monster@example.com',
                    } as User),
                } as any;
            });

        const user = await repository.findByEmail('monster@example.com');

        expect(model.findOne).toBeCalledTimes(1);
        expect(user).toEqual({
            name: 'James Silverman',
            email: 'monster@example.com',
        });
        expect(model.findOne).lastCalledWith({
            email: 'monster@example.com',
            verified: true
        });
    });

    it('findByVerification should return user', async () => {
        jest.spyOn(model, 'findOne')
            .mockImplementation((filter: any, callback: any) => {
                return {
                    exec: jest.fn().mockResolvedValue({
                        name: 'James Silverman',
                        email: 'monster@example.com',
                        verification: '123'
                    } as User),
                } as any;
            });

        const expires = new Date();
        const user = await repository.findByVerification('123', expires);

        expect(model.findOne).toBeCalledTimes(1);
        expect(user).toEqual({
            name: 'James Silverman',
            email: 'monster@example.com',
            verification: '123'
        });
        expect(model.findOne).lastCalledWith({
            verification: '123',
            verified: false,
            verificationExpires: { $gt: expires }
        });
    });

    it('findByUsername should return user', async () => {
        jest.spyOn(model, 'findOne')
            .mockImplementation((filter: any, callback: any) => {
                return {
                    exec: jest.fn().mockResolvedValue({
                        name: 'James Silverman',
                        username: 'james-silverman',
                        email: 'monster@example.com',
                        verification: '123'
                    } as User),
                } as any;
            });

        const user = await repository.findByUsername('james-silverman');

        expect(model.findOne).toBeCalledTimes(1);
        expect(user).toEqual({
            name: 'James Silverman',
            username: 'james-silverman',
            email: 'monster@example.com',
            verification: '123'
        });
        expect(model.findOne).lastCalledWith({ username: 'james-silverman' });
    });

    it('return one result', async () => {
        jest.spyOn(model, 'findById')
            .mockImplementation(
                (filter: any, callback: any) => {
                    return {
                        exec: jest.fn().mockResolvedValue({
                            name: 'James Silverman',
                            email: 'monster@example.com',
                        } as User),
                    } as any;
                },
            );

        const user = await repository.findById('James Silverman');

        expect(model.findById).lastCalledWith('James Silverman');
        expect(model.findById).toBeCalledTimes(1);
        expect(user).toEqual({
            name: 'James Silverman',
            email: 'monster@example.com',
        });
    });

    it('return a null result', async () => {
        jest.spyOn(model, 'findOne')
            .mockImplementation((filter: any, callback: any) => {
                return {
                    exec: jest.fn().mockResolvedValue(null) as any,
                } as any;
            });

        try {
            await repository.findById('Private');
        } catch (e) {
            expect(e).toBeDefined();
        }
    });

    it('exists method should return true if exists ', async () => {
        const existsSpy = jest
            .spyOn(model, 'exists')
            .mockImplementation((filter: any) => {
                return {
                    exec: jest.fn().mockResolvedValue({
                        _id: 'test',
                    } as any),
                } as any;
            });

        const result = await repository.existsByEmail('monster@example.com');

        expect(existsSpy).toBeCalledWith({ email: 'monster@example.com' });
        expect(existsSpy).toBeCalledTimes(1);
        expect(result).toBeTruthy();
    });

    it('should return false if not exists ', async () => {
        const existsSpy = jest
            .spyOn(model, 'exists')
            .mockImplementation((filter: any) => {
                return {
                    exec: jest.fn().mockResolvedValue(null),
                } as any;
            });

        const result = await repository.existsByEmail('monster@example.com');

        expect(existsSpy).toBeCalledWith({ email: 'monster@example.com' });
        expect(existsSpy).toBeCalledTimes(1);
        expect(result).toBeFalsy();
    });

    it('should update an existing user', async () => {
        const userData = {
            name: 'Wade Solman',
            username: 'WadeSolman',
            email: 'ws@wxample.com',
            password: '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
            phone: '1234567890'
        };

        jest.spyOn(model, 'findOneAndUpdate')
            .mockImplementation((filter: any) => {
                return {
                    exec: jest.fn().mockResolvedValue(userData),
                } as any;
            });

        const user = await repository.update('Wade Solman', userData);

        expect(user).toStrictEqual(userData);
        expect(model.findOneAndUpdate).toBeCalledTimes(1);
        expect(model.findOneAndUpdate).lastCalledWith(
            { _id: 'Wade Solman' },
            userData,
            { new: true }
        );
    });

    it('should delete an existing user', async () => {
        jest.spyOn(model, 'deleteOne')
            .mockImplementation((filter: any) => {
                return { exec: jest.fn() } as any;
            });

        const user = await repository.remove('Wade Solman');

        expect(model.deleteOne).toBeCalledTimes(1);
        expect(model.deleteOne).lastCalledWith({ _id: 'Wade Solman' });
    });
});
