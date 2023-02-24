/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import LocalAuth from './local.auth';
import UserEnum from '../../users/enums/user.enum';
import mockModel from '../../users/models/mock.model';
import { Model } from 'mongoose';
import User from '../../users/contracts/user.contract';

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
        mockModel.password = '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm';

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
});
