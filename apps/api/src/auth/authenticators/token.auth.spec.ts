/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import TokenAuth from './token.auth';
import UserEnum from '../../users/enums/user.enum';
import mockModel from '../../users/models/mock.model';
import { Model } from 'mongoose';
import User from '../../users/contracts/user.contract';
import { UnauthorizedException } from '@nestjs/common';
import JwtPayload from '../contracts/jwt-payload.contract';

describe('TokenAuth', () => {
    let model: Model<User>;
    let auth: TokenAuth;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                TokenAuth,
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
        auth = module.get<TokenAuth>(TokenAuth);
    });

    it('should be defined', () => {
        expect(auth).toBeDefined();
    });

    it('should authenticate the user with the provided JWT token', async () => {
        const mock = jest.spyOn(model, 'findOne')
            .mockImplementationOnce(({ credentials }: any) => {
                return Promise.resolve(mockModel) as any;
            });

        const user = await auth.authenticate({
            sub: '1234567890',
        } as JwtPayload);

        expect(user).toEqual(mockModel);
        expect(mock).toBeCalledWith({ _id: '1234567890', verified: true });
    });

    it('should throw unauthorized exception if jwt token is invalid', async () => {
        const mock = jest.spyOn(model, 'findOne')
            .mockImplementationOnce(({ credentials }: any) => {
                return Promise.resolve(undefined) as any;
            });

        try {
            await auth.authenticate({ sub: '1234567890' } as JwtPayload);
        } catch (error) {
            expect(error).toBeInstanceOf(UnauthorizedException);
            expect(error.message).toBe('Invalid credentials.');

            return;
        }

        fail('Did not throw NotFoundException');
    });
});
