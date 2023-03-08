/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import TicketsRepository from '../repositories/tickets.repository';
import TicketsService from './tickets.service';
import TicketEnum from '../enums/ticket.enum';
import UsersRepository from '../../users/repositories/users.repository';

describe('TicketsService', () => {
    let service: TicketsService;
    let repository: TicketsRepository;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                TicketsService,
                TicketsRepository,
                {
                    provide: TicketEnum.MODEL,
                    useValue: jest.fn()
                },
                {
                    provide: UsersRepository,
                    useValue: jest.fn()
                },
            ],
        }).compile();

        service = module.get<TicketsService>(TicketsService);
        repository = module.get<TicketsRepository>(TicketsRepository);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should create a new ticket', async () => {
        jest.spyOn(repository, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                title: 'I need some IT Assistance',
                body: 'Yes, please.',
                slug: 'it-assistance',
                channel: '6402697307e6d1fcd1d3146d',
                user: '63fe86f14f07c7e05f7db502'
            } as any),
        );

        const ticketData = {
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        };
        const ticket = await service.create(ticketData);

        expect(ticket).toStrictEqual(ticketData);
    });

    it('findOne should return ticket', async () => {
        jest.spyOn(repository, 'findById')
            .mockImplementationOnce((id: string) => {
                return {
                    title: 'I need some IT Assistance',
                    body: 'Yes, please.',
                    slug: 'it-assistance',
                    channel: '6402697307e6d1fcd1d3146d',
                    user: '63fe86f14f07c7e05f7db502'
                } as any;
            });

        const ticket = await service.findOne('fake-ticket-id');

        expect(repository.findById).toBeCalledTimes(1);
        expect(repository.findById).lastCalledWith('fake-ticket-id');
        expect(ticket).toEqual({
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        });
    });

    it('findAll should return a list of tickets', async () => {
        jest.spyOn(repository, 'findAll').mockImplementation(() => {
            return [{
                title: 'I need some IT Assistance',
                body: 'Yes, please.',
                slug: 'it-assistance',
                channel: '6402697307e6d1fcd1d3146d',
                user: '63fe86f14f07c7e05f7db502'
            }] as any;
        });

        const ticket = await service.findAll();

        expect(ticket).toEqual([{
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        }]);
    });

    it('should update an existing ticket', async () => {
        const ticketData = {
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        };

        jest.spyOn(repository, 'update')
            .mockImplementationOnce((_id: string, dto: any) => {
                return ticketData as any;
            });

        const ticket = await repository.update('fake-ticket-id', ticketData);

        expect(ticket).toStrictEqual(ticketData);
        expect(repository.update).toBeCalledTimes(1);
        expect(repository.update).lastCalledWith('fake-ticket-id', ticketData);
    });

    it('should delete an existing ticket', async () => {
        jest.spyOn(repository, 'remove')
            .mockImplementationOnce((_id: string) => {
                return null as any;
            });

        await repository.remove('fake-ticket-id');

        expect(repository.remove).toBeCalledTimes(1);
        expect(repository.remove).lastCalledWith('fake-ticket-id');
    });
});
