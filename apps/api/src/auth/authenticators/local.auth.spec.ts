/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import LocalAuth from './local.auth';
import UserEnum from '../../users/enums/user.enum';
import mockModel from '../../users/models/mock.model';
import { Model } from 'mongoose';
import User from '../../users/contracts/user.contract';
import { ConflictException, NotFoundException, UnauthorizedException } from '@nestjs/common';
import Login from '../enums/login.enum';

describe('LocalAuth', () => {
    let model: Model<User>;
    let auth: LocalAuth;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                LocalAuth,
                {
                    provide: UserEnum.MODEL,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                        findOne: jest.fn(),
                        save: jest.fn(),
                        create: jest.fn(),
                        exec: jest.fn(),
                    }
                }]
        }).compile();

        model = module.get<Model<User>>(UserEnum.MODEL);
        auth = module.get<LocalAuth>(LocalAuth);
    });

    it('should be defined', () => {
        expect(auth).toBeDefined();
    });

    it('should authenticate the user with the provided credentials', async () => {
        mockModel.password = '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm'; // password

        jest.spyOn(model, 'findOne')
            .mockImplementationOnce(({ email, verified }: any) => {
                return Promise.resolve(mockModel) as any;
            });

        const user = await auth.authenticate({
            email: 'james@monster.com',
            password: 'password'
        });

        expect(user).toEqual(mockModel);
    });

    it('should throw not found exception if user credentials are invalid', async () => {
        mockModel.password = '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm'; // password

        jest.spyOn(model, 'findOne')
            .mockImplementationOnce(({ email, verified }: any) => {
                return Promise.resolve(undefined) as any;
            });

        try {
            await auth.authenticate({
                email: 'james@monster.com',
                password: 'password'
            });
        } catch (error) {
            expect(error).toBeInstanceOf(NotFoundException);
            expect(error.message).toBe('User with these credentials was not found.');
        }
    });

    it('should throw unauthorized exception if user password is invalid', async () => {
        mockModel.password = '$2a$08$9qx5nogMjutjDU7PfyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm'; // password

        jest.spyOn(model, 'findOne')
            .mockImplementationOnce(({ email, verified }: any) => {
                return Promise.resolve(mockModel) as any;
            });

        try {
            await auth.authenticate({
                email: 'james@monster.com',
                password: 'password'
            });
        } catch (error) {
            expect(error).toBeInstanceOf(UnauthorizedException);
            expect(error.message).toBe('Invalid email or password.');

            return;
        }

        fail('Did not throw UnauthorizedException');
    });

    it('should throw conflict exception if user is blocked', async () => {
        mockModel.blockedAt = new Date();
        mockModel.password = '$2a$08$9qx5nogMjutjDU7PfyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm'; // password

        jest.spyOn(model, 'findOne')
            .mockImplementationOnce(({ email, verified }: any) => {
                return Promise.resolve(mockModel) as any;
            });

        try {
            await auth.authenticate({
                email: 'james@monster.com',
                password: 'password'
            });
        } catch (error) {
            expect(error).toBeInstanceOf(ConflictException);
            expect(error.message).toBe('User has been blocked from accessing account.');

            return;
        }

        fail('Did not throw ConflictException');
    });

    it('should throw conflict exception if user attempted to login too many times', async () => {
        mockModel.blockedAt = undefined;
        mockModel.loginAttempts = Login.LOGIN_ATTEMPTS_TO_BLOCK;
        mockModel.password = '$2a$08$9qx5nogMjutjDU7PfyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm'; // password

        jest.spyOn(model, 'findOne')
            .mockImplementationOnce(({ email, verified }: any) => {
                return Promise.resolve(mockModel) as any;
            });

        try {
            await auth.authenticate({
                email: 'james@monster.com',
                password: 'password'
            });
        } catch (error) {
            expect(error).toBeInstanceOf(ConflictException);
            expect(error.message).toBe('User blocked becuase of too many attempts to login.');

            return;
        }

        fail('Did not throw ConflictException');
    });
});
