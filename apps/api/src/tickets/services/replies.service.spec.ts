/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import RepliesService from './replies.service';
import RepliesRepository from '../repositories/replies.repository';

describe('RepliesService', () => {
    let service: RepliesService;
    let repository: RepliesRepository;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                RepliesService,
                {
                    provide: RepliesRepository,
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

        service = module.get<RepliesService>(RepliesService);
        repository = module.get<RepliesRepository>(RepliesRepository);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should create a new reply', async () => {
        jest.spyOn(repository, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                user: 'Winston Caleb',
                body: 'Help related to computers.',
                ticket: 'fake-ticket-uid',
            } as any),
        );

        const replyData = {
            user: 'Winston Caleb',
            body: 'Help related to computers.',
            ticket: 'fake-ticket-uid',
        };
        const reply = await service.create(replyData);

        expect(reply).toStrictEqual(replyData);
    });

    it('findOne should return reply', async () => {
        jest.spyOn(repository, 'findById')
            .mockImplementationOnce((id: string) => {
                return {
                    id: 'fake-reply-id',
                    user: 'Winston Caleb',
                    body: 'Help related to computers.',
                    ticket: 'fake-ticket-uid',
                } as any;
            });

        const reply = await service.findOne('fake-reply-id');

        expect(repository.findById).toBeCalledTimes(1);
        expect(repository.findById).lastCalledWith('fake-reply-id');
        expect(reply).toEqual({
            id: 'fake-reply-id',
            user: 'Winston Caleb',
            body: 'Help related to computers.',
            ticket: 'fake-ticket-uid',
        });
    });

    it('findAll should return reply', async () => {
        jest.spyOn(repository, 'findAll')
            .mockImplementationOnce(() => {
                return [{
                    id: 'fake-reply-id',
                    user: 'Winston Caleb',
                    body: 'Help related to computers.',
                    ticket: 'fake-ticket-uid',
                }] as any;
            });

        const reply = await service.findAll();

        expect(repository.findAll).toBeCalledTimes(1);
        expect(reply).toEqual([{
            id: 'fake-reply-id',
            user: 'Winston Caleb',
            body: 'Help related to computers.',
            ticket: 'fake-ticket-uid',
        }]);
    });

    it('should update an existing reply', async () => {
        const replyData = {
            id: 'fake-reply-id',
            user: 'Winston Caleb',
            body: 'Help related to computers.',
            ticket: 'fake-ticket-uid',
        };

        jest.spyOn(repository, 'update')
            .mockImplementationOnce((_id: string, dto: any) => {
                return replyData as any;
            });

        const reply = await repository.update('IT Assistance', replyData);

        expect(reply).toStrictEqual(replyData);
        expect(repository.update).toBeCalledTimes(1);
        expect(repository.update).lastCalledWith('IT Assistance', replyData);
    });

    it('should delete an existing reply', async () => {
        jest.spyOn(repository, 'remove')
            .mockImplementationOnce((_id: string) => {
                return null as any;
            });

        await repository.remove('IT Assistance');

        expect(repository.remove).toBeCalledTimes(1);
        expect(repository.remove).lastCalledWith('IT Assistance');
    });
});
