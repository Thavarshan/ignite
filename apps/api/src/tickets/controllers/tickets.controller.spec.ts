import { Test, TestingModule } from '@nestjs/testing';
import TicketsService from '../services/tickets.service';
import TicketsController from './tickets.controller';

describe('TicketsController', () => {
    let service: TicketsService;
    let controller: TicketsController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [TicketsController],
            providers: [
                {
                    provide: TicketsService,
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

        service = module.get<TicketsService>(TicketsService);
        controller = module.get<TicketsController>(TicketsController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });

    it('should create a new ticket', async () => {
        jest.spyOn(service, 'create')
            .mockImplementationOnce(() => Promise.resolve({
                title: 'I need some IT Assistance',
                body: 'Yes, please.',
                slug: 'it-assistance',
                channel: '6402697307e6d1fcd1d3146d',
                user: '63fe86f14f07c7e05f7db502'
            } as any));

        const ticketData = {
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        };
        const ticket = await controller.create(ticketData);

        expect(ticket).toStrictEqual({ ...ticketData, slug: 'it-assistance' });
    });

    it('findOne should return ticket', async () => {
        jest.spyOn(service, 'findOne')
            .mockImplementationOnce(() => {
                return {
                    title: 'I need some IT Assistance',
                    body: 'Yes, please.',
                    slug: 'it-assistance',
                    channel: '6402697307e6d1fcd1d3146d',
                    user: '63fe86f14f07c7e05f7db502'
                } as any;
            });

        const ticket = await controller.findOne('IT Assistance');

        expect(service.findOne).toBeCalledTimes(1);
        expect(service.findOne).lastCalledWith('IT Assistance');
        expect(ticket).toEqual({
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        });
    });

    it('findAll should return ticket', async () => {
        jest.spyOn(service, 'findAll')
            .mockImplementationOnce(() => {
                return [{
                    title: 'I need some IT Assistance',
                    body: 'Yes, please.',
                    slug: 'it-assistance',
                    channel: '6402697307e6d1fcd1d3146d',
                    user: '63fe86f14f07c7e05f7db502'
                }] as any;
            });

        const tickets = await controller.findAll({ user: {} });

        expect(service.findAll).toBeCalledTimes(1);
        expect(tickets).toEqual({
            data: [{
                title: 'I need some IT Assistance',
                body: 'Yes, please.',
                slug: 'it-assistance',
                channel: '6402697307e6d1fcd1d3146d',
                user: '63fe86f14f07c7e05f7db502'
            }]
        });
    });

    it('should update an existing ticket', async () => {
        const ticketData = {
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        };

        jest.spyOn(service, 'update')
            .mockImplementationOnce(() => {
                return ticketData as any;
            });

        const ticket = await controller.update('IT Assistance', ticketData);

        expect(ticket).toStrictEqual(ticketData);
        expect(service.update).toBeCalledTimes(1);
        expect(service.update).lastCalledWith('IT Assistance', ticketData);
    });

    it('should delete an existing ticket', async () => {
        jest.spyOn(service, 'remove')
            .mockImplementationOnce(() => {
                return null as any;
            });

        await controller.remove('IT Assistance');

        expect(service.remove).toBeCalledTimes(1);
        expect(service.remove).lastCalledWith('IT Assistance');
    });
});
