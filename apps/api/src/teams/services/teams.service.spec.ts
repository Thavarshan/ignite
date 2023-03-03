/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import TeamsService from './teams.service';
import TeamsRepository from '../repositories/teams.repository';
import mockModel from '../models/mock.model';

describe('TeamsService', () => {
    let service: TeamsService;
    let repository: TeamsRepository;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                TeamsService,
                {
                    provide: TeamsRepository,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                        findById: jest.fn(),
                        create: jest.fn(),
                        findAll: jest.fn(),
                        findByEmail: jest.fn(),
                        findByTeamname: jest.fn(),
                        findByVerification: jest.fn(),
                        existsByEmail: jest.fn(),
                        update: jest.fn(),
                        remove: jest.fn(),
                        exec: jest.fn()
                    }
                }
            ],
        }).compile();

        service = module.get<TeamsService>(TeamsService);
        repository = module.get<TeamsRepository>(TeamsRepository);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should create a new team', async () => {
        jest.spyOn(repository, 'create').mockImplementationOnce(() =>
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
        const team = await service.create(teamData);

        expect(team).toStrictEqual(teamData);
    });

    it('findOne should return team', async () => {
        jest.spyOn(repository, 'findById')
            .mockImplementationOnce((id: string) => {
                return {
                    name: 'James Silverman',
                    email: 'monster@example.com',
                } as any;
            });

        const team = await service.findOne('James Silverman');

        expect(repository.findById).toBeCalledTimes(1);
        expect(repository.findById).lastCalledWith('James Silverman');
        expect(team).toEqual({
            name: 'James Silverman',
            email: 'monster@example.com',
        });
    });

    it('findAll should return team', async () => {
        jest.spyOn(repository, 'findAll')
            .mockImplementationOnce(() => {
                return [{
                    name: 'James Silverman',
                    email: 'monster@example.com',
                }] as any;
            });

        const team = await service.findAll();

        expect(repository.findAll).toBeCalledTimes(1);
        expect(team).toEqual([{
            name: 'James Silverman',
            email: 'monster@example.com',
        }]);
    });

    it('should update an existing team', async () => {
        const teamData = {
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            description: 'Team description',
            phone: '1234567890',
            owner: '63f9e922f9e75201ca9f9af3'
        };

        jest.spyOn(repository, 'update')
            .mockImplementationOnce((_id: string, dto: any) => {
                return teamData as any;
            });

        const team = await repository.update('Wade Solman', teamData);

        expect(team).toStrictEqual(teamData);
        expect(repository.update).toBeCalledTimes(1);
        expect(repository.update).lastCalledWith('Wade Solman', teamData);
    });

    it('should delete an existing team', async () => {
        jest.spyOn(repository, 'remove')
            .mockImplementationOnce((_id: string) => {
                return null as any;
            });

        await repository.remove('Wade Solman');

        expect(repository.remove).toBeCalledTimes(1);
        expect(repository.remove).lastCalledWith('Wade Solman');
    });
});
