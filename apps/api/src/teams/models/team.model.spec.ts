/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import TeamContract from '../contracts/team.contract';
import TeamEnum from '../enums/team.enum';
import { Model } from 'mongoose';

describe('TeamModel', () => {
    let model: Model<TeamContract>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                {
                    provide: TeamEnum.MODEL,
                    useValue: {
                        findOne: jest.fn(),
                        exists: jest.fn(),
                        create: jest.fn(),
                    },
                },
            ],
        }).compile();

        model = module.get<Model<TeamContract>>(TeamEnum.MODEL);
    });

    it('should be defined', () => {
        expect(model).toBeDefined();
    });

    it('should save new data', async () => {
        const sampleData = {
            name: 'James',
            email: 'JjTheMan@example.com',
            phone: '12345678901',
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
