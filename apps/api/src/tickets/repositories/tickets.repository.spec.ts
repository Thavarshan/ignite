/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import TicketsRepository from './tickets.repository';
import { Model, Query } from 'mongoose';
import Ticket from '../contracts/ticket.contract';
import TicketEnum from '../enums/ticket.enum';
import UsersRepository from '../../users/repositories/users.repository';
import { fail } from 'assert';

describe('TicketsRepository', () => {
    let repository: TicketsRepository;
    let users: UsersRepository;
    let model: Model<Ticket>;
    let query: Query<any, Ticket>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                TicketsRepository,
                {
                    provide: UsersRepository,
                    useValue: {
                        new: jest.fn(),
                        constructor: jest.fn(),
                        findById: jest.fn(),
                        exec: jest.fn(),
                    }
                },
                {
                    provide: TicketEnum.MODEL,
                    useValue: {
                        new: jest.fn(),
                        constructor: jest.fn(),
                        find: jest.fn(),
                        findOne: jest.fn(),
                        findById: jest.fn(),
                        create: jest.fn(),
                        findByIdAndUpdate: jest.fn(),
                        findByIdAndDelete: jest.fn(),
                        exists: jest.fn(),
                        populate: jest.fn(),
                        exec: jest.fn(),
                    }
                },
                {
                    provide: Query,
                    useValue: {
                        new: jest.fn(),
                        constructor: jest.fn(),
                        populate: jest.fn(),
                        exec: jest.fn(),
                    }
                }
            ],
        }).compile();

        repository = module.get<TicketsRepository>(TicketsRepository);
        users = module.get<UsersRepository>(UsersRepository);
        model = module.get<Model<Ticket>>(TicketEnum.MODEL);
        query = module.get<Query<any, Ticket>>(Query);
    });

    it('should be defined', () => {
        expect(repository).toBeDefined();
    });

    it('should create a new ticket', async () => {
        jest.spyOn(users, 'findById').mockImplementationOnce(() =>
            Promise.resolve({
                user: { team: '63fe86f14f07c7e05f7db502' }
            } as any),
        );

        jest.spyOn(model, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                title: 'I need some IT Assistance',
                body: 'Yes, please.',
                slug: 'it-assistance',
                channel: '6402697307e6d1fcd1d3146d',
                user: '63fe86f14f07c7e05f7db502',
                team: '63fe86f14f07c7e05f7db502'
            } as any),
        );

        const ticketData = {
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        };
        const ticket = await repository.create(ticketData);

        expect(ticket).toStrictEqual({ ...ticketData, team: '63fe86f14f07c7e05f7db502' });
    });

    it('findAll should return a list of tickets', async () => {
        jest.spyOn(query, 'populate').mockReturnThis();
        jest.spyOn(query, 'exec').mockImplementation(() => {
            return Promise.resolve([{
                title: 'I need some IT Assistance',
                body: 'Yes, please.',
                slug: 'it-assistance',
                channel: '6402697307e6d1fcd1d3146d',
                user: '63fe86f14f07c7e05f7db502'
            }]);
        });
        jest.spyOn(model, 'find').mockImplementation(() => query);

        const ticket = await repository.findAll(undefined);

        expect(model.find).toBeCalledTimes(1);
        expect(ticket).toEqual([{
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        }]);
        expect(model.find).lastCalledWith(null);
    });

    it('findBySlug should return ticket', async () => {
        jest.spyOn(query, 'populate').mockReturnThis();
        jest.spyOn(query, 'exec').mockImplementation(() => {
            return Promise.resolve({
                title: 'I need some IT Assistance',
                body: 'Yes, please.',
                slug: 'it-assistance',
                channel: '6402697307e6d1fcd1d3146d',
                user: '63fe86f14f07c7e05f7db502'
            });
        });
        jest.spyOn(model, 'findOne').mockImplementation(() => query);

        const ticket = await repository.findBySlug('it-assistance');

        expect(model.findOne).toBeCalledTimes(1);
        expect(ticket).toEqual({
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        });
        expect(model.findOne).lastCalledWith({ slug: 'it-assistance' });
    });

    it('return one result', async () => {
        jest.spyOn(query, 'populate').mockReturnThis();
        jest.spyOn(query, 'exec').mockImplementation(() => {
            return Promise.resolve({
                title: 'I need some IT Assistance',
                body: 'Yes, please.',
                slug: 'it-assistance',
                channel: '6402697307e6d1fcd1d3146d',
                user: '63fe86f14f07c7e05f7db502'
            });
        });
        jest.spyOn(model, 'findById').mockImplementation(() => query);

        const ticket = await repository.findById('I need some IT Assistance');

        expect(model.findById).lastCalledWith('I need some IT Assistance');
        expect(model.findById).toBeCalledTimes(1);
        expect(ticket).toEqual({
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        });
    });

    it('return a null result', async () => {
        jest.spyOn(model, 'findById')
            .mockImplementation((filter: any, callback: any) => {
                return {
                    exec: jest.fn().mockResolvedValue(null) as any,
                } as any;
            });

        try {
            await repository.findById('Private');
        } catch (e) {
            expect(e).toBeDefined();

            return;
        }

        fail('Ticket not found exception was not called.');
    });

    it('should update an existing ticket', async () => {
        const ticketData = {
            title: 'I need some IT Assistance',
            body: 'Yes, please.',
            slug: 'it-assistance',
            channel: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        };

        jest.spyOn(model, 'findByIdAndUpdate')
            .mockImplementation((filter: any) => {
                return {
                    exec: jest.fn().mockResolvedValue(ticketData),
                } as any;
            });

        const ticket = await repository.update('Wade Solman', ticketData);

        expect(ticket).toStrictEqual(ticketData);
        expect(model.findByIdAndUpdate).toBeCalledTimes(1);
        expect(model.findByIdAndUpdate).lastCalledWith(
            'Wade Solman',
            ticketData,
            { new: true }
        );
    });

    it('should delete an existing ticket', async () => {
        jest.spyOn(model, 'findByIdAndDelete')
            .mockImplementation((filter: any) => {
                return { exec: jest.fn() } as any;
            });

        const ticket = await repository.remove('Wade Solman');

        expect(model.findByIdAndDelete).toBeCalledTimes(1);
        expect(model.findByIdAndDelete).lastCalledWith('Wade Solman');
    });
});
