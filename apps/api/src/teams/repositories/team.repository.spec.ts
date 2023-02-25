/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import TeamRepository from './team.repository';
import { Model } from 'mongoose';
import Team from '../contracts/team.contract';
import TeamEnum from '../enums/team.enum';
import mockModel from '../models/mock.model';

describe('TeamRepository', () => {
    let repository: TeamRepository;
    let model: Model<Team>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                TeamRepository,
                {
                    provide: TeamEnum.MODEL,
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

        repository = module.get<TeamRepository>(TeamRepository);
        model = module.get<Model<Team>>(TeamEnum.MODEL);
    });

    it('should be defined', () => {
        expect(repository).toBeDefined();
    });

    it('should create a new team', async () => {
        jest.spyOn(model, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                name: 'Wade Solman',
                email: 'ws@wxample.com',
                description: 'Wades Team',
                phone: '1234567890',
                owner: '63f9e922f9e75201ca9f9af3'
            } as any),
        );

        const teamData = {
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            description: 'Wades Team',
            phone: '1234567890',
            owner: '63f9e922f9e75201ca9f9af3'
        };
        const team = await repository.create(teamData);

        expect(team).toStrictEqual(teamData);
    });

    it('findBySlug should return team', async () => {
        jest.spyOn(model, 'findOne')
            .mockImplementation((filter: any, callback: any) => {
                return {
                    exec: jest.fn().mockResolvedValue({
                        name: 'James Silverman',
                        email: 'monster@example.com',
                        slug: 'james-silverman'
                    } as Team),
                } as any;
            });

        const team = await repository.findBySlug('james-silverman');

        expect(model.findOne).toBeCalledTimes(1);
        expect(team).toEqual({
            name: 'James Silverman',
            email: 'monster@example.com',
            slug: 'james-silverman'
        });
        expect(model.findOne).lastCalledWith({ slug: 'james-silverman' });
    });

    it('return one result', async () => {
        jest.spyOn(model, 'findById')
            .mockImplementation(
                (filter: any, callback: any) => {
                    return {
                        exec: jest.fn().mockResolvedValue({
                            name: 'James Silverman',
                            email: 'monster@example.com',
                        } as Team),
                    } as any;
                },
            );

        const team = await repository.findById('James Silverman');

        expect(model.findById).lastCalledWith('James Silverman');
        expect(model.findById).toBeCalledTimes(1);
        expect(team).toEqual({
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

    it('should update an existing team', async () => {
        const teamData = {
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            description: 'Wades Team',
            phone: '1234567890',
            owner: '63f9e922f9e75201ca9f9af3'
        };

        jest.spyOn(model, 'findOneAndUpdate')
            .mockImplementation((filter: any) => {
                return {
                    exec: jest.fn().mockResolvedValue(teamData),
                } as any;
            });

        const team = await repository.update('Wade Solman', teamData);

        expect(team).toStrictEqual(teamData);
        expect(model.findOneAndUpdate).toBeCalledTimes(1);
        expect(model.findOneAndUpdate).lastCalledWith(
            { _id: 'Wade Solman' },
            teamData,
            { new: true }
        );
    });

    it('should delete an existing team', async () => {
        jest.spyOn(model, 'deleteOne')
            .mockImplementation((filter: any) => {
                return { exec: jest.fn() } as any;
            });

        const team = await repository.remove('Wade Solman');

        expect(model.deleteOne).toBeCalledTimes(1);
        expect(model.deleteOne).lastCalledWith({ _id: 'Wade Solman' });
    });
});
