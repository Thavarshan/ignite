import { Test, TestingModule } from '@nestjs/testing';
import ChannelsService from '../services/channels.service';
import ChannelsController from './channels.controller';

describe('ChannelsController', () => {
    let service: ChannelsService;
    let controller: ChannelsController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [ChannelsController],
            providers: [
                {
                    provide: ChannelsService,
                    useValue: {
                        new: jest.fn(),
                        constructor: jest.fn(),
                        findOne: jest.fn(),
                        findAll: jest.fn(),
                        create: jest.fn(),
                        update: jest.fn(),
                        remove: jest.fn(),
                    }
                }
            ],
        }).compile();

        service = module.get<ChannelsService>(ChannelsService);
        controller = module.get<ChannelsController>(ChannelsController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });

    it('should create a new channel', async () => {
        jest.spyOn(service, 'create').mockImplementationOnce(() =>
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
        const channel = await controller.create(channelData);

        expect(channel).toStrictEqual({ ...channelData, slug: 'it-assistance' });
    });

    it('findOne should return channel', async () => {
        jest.spyOn(service, 'findOne')
            .mockImplementationOnce(() => {
                return {
                    name: 'IT Assistance',
                    description: 'Help related to computers.',
                    slug: 'it-assistance',
                } as any;
            });

        const channel = await controller.findOne('IT Assistance');

        expect(service.findOne).toBeCalledTimes(1);
        expect(service.findOne).lastCalledWith('IT Assistance');
        expect(channel).toEqual({
            name: 'IT Assistance',
            description: 'Help related to computers.',
            slug: 'it-assistance',
        });
    });

    it('findAll should return channel', async () => {
        jest.spyOn(service, 'findAll')
            .mockImplementationOnce(() => {
                return [{
                    name: 'IT Assistance',
                    description: 'Help related to computers.',
                    slug: 'it-assistance',
                }] as any;
            });

        const channels = await controller.findAll();

        expect(service.findAll).toBeCalledTimes(1);
        expect(channels).toEqual({
            data: [{
                name: 'IT Assistance',
                description: 'Help related to computers.',
                slug: 'it-assistance',
            }]
        });
    });

    it('should update an existing channel', async () => {
        const channelData = {
            name: 'IT Assistance',
            description: 'Help related to computers.',
            slug: 'it-assistance',
        };

        jest.spyOn(service, 'update')
            .mockImplementationOnce(() => {
                return channelData as any;
            });

        const channel = await controller.update('IT Assistance', channelData);

        expect(channel).toStrictEqual(channelData);
        expect(service.update).toBeCalledTimes(1);
        expect(service.update).lastCalledWith('IT Assistance', channelData);
    });

    it('should delete an existing channel', async () => {
        jest.spyOn(service, 'remove')
            .mockImplementationOnce(() => {
                return null as any;
            });

        await controller.remove('IT Assistance');

        expect(service.remove).toBeCalledTimes(1);
        expect(service.remove).lastCalledWith('IT Assistance');
    });
});
