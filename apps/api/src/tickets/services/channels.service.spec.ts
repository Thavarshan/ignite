/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import ChannelsService from './channels.service';
import ChannelsRepository from '../repositories/channels.repository';

describe('ChannelsService', () => {
    let service: ChannelsService;
    let repository: ChannelsRepository;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                ChannelsService,
                {
                    provide: ChannelsRepository,
                    useValue: {
                        new: jest.fn(),
                        constructor: jest.fn(),
                        findById: jest.fn(),
                        create: jest.fn(),
                        findAll: jest.fn(),
                        update: jest.fn(),
                        remove: jest.fn(),
                        exec: jest.fn()
                    }
                }
            ],
        }).compile();

        service = module.get<ChannelsService>(ChannelsService);
        repository = module.get<ChannelsRepository>(ChannelsRepository);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should create a new channel', async () => {
        jest.spyOn(repository, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                name: 'IT Assistance',
                description: 'Help related to computers.',
                slug: 'it-assistance',
            } as any),
        );

        const channelData = {
            name: 'IT Assistance',
            description: 'Help related to computers.',
        };
        const channel = await service.create(channelData);

        expect(channel).toStrictEqual({ ...channelData, slug: 'it-assistance' });
    });

    it('findOne should return channel', async () => {
        jest.spyOn(repository, 'findById')
            .mockImplementationOnce((id: string) => {
                return {
                    name: 'IT Assistance',
                    description: 'Help related to computers.',
                    slug: 'it-assistance',
                } as any;
            });

        const channel = await service.findOne('James Silverman');

        expect(repository.findById).toBeCalledTimes(1);
        expect(repository.findById).lastCalledWith('James Silverman');
        expect(channel).toEqual({
            name: 'IT Assistance',
            description: 'Help related to computers.',
            slug: 'it-assistance',
        });
    });

    it('findAll should return channel', async () => {
        jest.spyOn(repository, 'findAll')
            .mockImplementationOnce(() => {
                return [{
                    name: 'IT Assistance',
                    description: 'Help related to computers.',
                    slug: 'it-assistance',
                }] as any;
            });

        const channel = await service.findAll();

        expect(repository.findAll).toBeCalledTimes(1);
        expect(channel).toEqual([{
            name: 'IT Assistance',
            description: 'Help related to computers.',
            slug: 'it-assistance',
        }]);
    });

    it('should update an existing channel', async () => {
        const channelData = {
            name: 'IT Assistance',
            description: 'Help related to computers.',
        };

        jest.spyOn(repository, 'update')
            .mockImplementationOnce((_id: string, dto: any) => {
                return channelData as any;
            });

        const channel = await repository.update('IT Assistance', channelData);

        expect(channel).toStrictEqual(channelData);
        expect(repository.update).toBeCalledTimes(1);
        expect(repository.update).lastCalledWith('IT Assistance', channelData);
    });

    it('should delete an existing channel', async () => {
        jest.spyOn(repository, 'remove')
            .mockImplementationOnce((_id: string) => {
                return null as any;
            });

        await repository.remove('IT Assistance');

        expect(repository.remove).toBeCalledTimes(1);
        expect(repository.remove).lastCalledWith('IT Assistance');
    });
});
