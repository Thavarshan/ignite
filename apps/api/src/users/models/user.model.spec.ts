/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import { anyFunction } from 'jest-mock-extended';
import UserSchema from '../schemas/user.schema';
import UserContract from '../contracts/user.contract';
import UserEnum from '../enums/user.enum';
import Roles from '../enums/roles.enum';
import { Model } from 'mongoose';

describe('UserModel', () => {
    let model: Model<UserContract>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                {
                    provide: UserEnum.MODEL,
                    useValue: {
                        findOne: jest.fn(),
                        exists: jest.fn(),
                        create: jest.fn(),
                    },
                },
            ],
        }).compile();

        model = module.get<Model<UserContract>>(UserEnum.MODEL);
    });

    it('should be defined', () => {
        expect(model).toBeDefined();
    });

    it('should save new data', async () => {
        const sampleData = {
            name: 'James',
            email: 'JjTheMan@example.com',
            password: 'mysecret',
        };

        const saveSpy = jest.spyOn(model, 'create').mockImplementation(() =>
            Promise.resolve({
                _id: '123',
                ...sampleData,
            } as any),
        );

        const result: any = await model.create({ ...sampleData, roles: [Roles.USER] });
        expect(saveSpy).toBeCalledWith({ ...sampleData, roles: [Roles.USER] });
        expect(result._id).toBeDefined();
    });
});
