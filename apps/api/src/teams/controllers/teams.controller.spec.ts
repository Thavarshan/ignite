/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import TeamsController from './teams.controller';
import TeamService from '../services/team.service';
import mockModel from '../models/mock.model';

describe('TeamsController', () => {
    let service: TeamService;
    let controller: TeamsController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [TeamsController],
            providers: [
                {
                    provide: TeamService,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                        findOne: jest.fn(),
                        findAll: jest.fn(),
                        create: jest.fn(),
                        update: jest.fn(),
                        remove: jest.fn(),
                    }
                }
            ],
        }).compile();

        service = module.get<TeamService>(TeamService);
        controller = module.get<TeamsController>(TeamsController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });

    it('should create a new team', async () => {
        jest.spyOn(service, 'create').mockImplementationOnce(() =>
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
        const team = await controller.create(teamData);

        expect(team).toStrictEqual(teamData);
    });

    it('findOne should return team', async () => {
        jest.spyOn(service, 'findOne')
            .mockImplementationOnce((id: string) => {
                return {
                    name: 'James Silverman',
                    email: 'monster@example.com',
                } as any;
            });

        const team = await controller.findOne('James Silverman');

        expect(service.findOne).toBeCalledTimes(1);
        expect(service.findOne).lastCalledWith('James Silverman');
        expect(team).toEqual({
            name: 'James Silverman',
            email: 'monster@example.com',
        });
    });

    it('findAll should return team', async () => {
        jest.spyOn(service, 'findAll')
            .mockImplementationOnce(() => {
                return [{
                    name: 'James Silverman',
                    email: 'monster@example.com',
                }] as any;
            });

        const teams = await controller.findAll();

        expect(service.findAll).toBeCalledTimes(1);
        expect(teams).toEqual({
            data: [{
                name: 'James Silverman',
                email: 'monster@example.com',
            }]
        });
    });

    it('should update an existing team', async () => {
        const teamData = {
            name: 'Wade Solman',
            email: 'ws@wxample.com',
            description: 'Wades Team',
            phone: '1234567890',
            owner: '63f9e922f9e75201ca9f9af3'
        };

        jest.spyOn(service, 'update')
            .mockImplementationOnce((_id: string, dto: any) => {
                return teamData as any;
            });

        const team = await controller.update('Wade Solman', teamData);

        expect(team).toStrictEqual(teamData);
        expect(service.update).toBeCalledTimes(1);
        expect(service.update).lastCalledWith('Wade Solman', teamData);
    });

    it('should delete an existing team', async () => {
        jest.spyOn(service, 'remove')
            .mockImplementationOnce((_id: string) => {
                return null as any;
            });

        await controller.remove('Wade Solman');

        expect(service.remove).toBeCalledTimes(1);
        expect(service.remove).lastCalledWith('Wade Solman');
    });
});
