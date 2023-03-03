/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import { Model } from 'mongoose';
import PasswordEnum from '../enums/password.enum';
import PasswordResetRequest from '../models/password-reset-request.model';
import PasswordResetRequestContract from '../contracts/password-reset-request.contract';

describe('PasswordResetRequestModel', () => {
    let model: Model<PasswordResetRequestContract>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                {
                    provide: PasswordEnum.MODEL,
                    useValue: {
                        findOne: jest.fn(),
                        exists: jest.fn(),
                        create: jest.fn(),
                    },
                },
            ],
        }).compile();

        model = module.get<Model<PasswordResetRequestContract>>(PasswordEnum.MODEL);
    });

    it('should be defined', () => {
        expect(model).toBeDefined();
    });

    it('should save new data', async () => {
        const sampleData = {
            email: 'JjTheMan@example.com',
            token: 'mysecret',
        };

        const saveSpy = jest.spyOn(model, 'create').mockImplementation(() =>
            Promise.resolve({
                _id: '123',
                ...sampleData,
            } as any),
        );

        const result: any = await model.create(sampleData);

        expect(saveSpy).toBeCalledWith(sampleData);
        expect(result._id).toBeDefined();
    });
});
