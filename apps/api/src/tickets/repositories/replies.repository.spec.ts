/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import ReplysRepository from './replies.repository';
import { Model } from 'mongoose';
import Reply from '../contracts/reply.contract';
import ReplyEnum from '../enums/reply.enum';
import UsersRepository from '../../users/repositories/users.repository';
import { fail } from 'assert';

describe('ReplysRepository', () => {
    let repository: ReplysRepository;
    let model: Model<Reply>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                ReplysRepository,
                {
                    provide: ReplyEnum.MODEL,
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
            ],
        }).compile();

        repository = module.get<ReplysRepository>(ReplysRepository);
        model = module.get<Model<Reply>>(ReplyEnum.MODEL);
    });

    it('should be defined', () => {
        expect(repository).toBeDefined();
    });

    it('should create a new reply', async () => {
        jest.spyOn(model, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                body: 'Yes, please.',
                ticket: '6402697307e6d1fcd1d3146d',
                user: '63fe86f14f07c7e05f7db502',
            } as any),
        );

        const replyData = {
            body: 'Yes, please.',
            ticket: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        };
        const reply = await repository.create(replyData);

        expect(reply).toStrictEqual(replyData);
    });

    it('findAll should return a list of replies', async () => {
        jest.spyOn(model, 'find').mockImplementation(() => {
            return {
                populate: jest.fn().mockImplementation(() => ({
                    exec: jest.fn().mockImplementation(() => ([{
                        body: 'Yes, please.',
                        ticket: '6402697307e6d1fcd1d3146d',
                        user: '63fe86f14f07c7e05f7db502'
                    }]))
                }))
            } as any;
        });

        const reply = await repository.findAll();

        expect(model.find).toBeCalledTimes(1);
        expect(reply).toEqual([{
            body: 'Yes, please.',
            ticket: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        }]);
        expect(model.find).lastCalledWith(null);
    });

    it('return one result', async () => {
        jest.spyOn(model, 'findById').mockImplementation(() => {
            return {
                populate: jest.fn().mockImplementation(() => ({
                    exec: jest.fn().mockImplementation(() => ({
                        body: 'Yes, please.',
                        ticket: '6402697307e6d1fcd1d3146d',
                        user: '63fe86f14f07c7e05f7db502'
                    }))
                }))
            } as any;
        });

        const reply = await repository.findById('I need some IT Assistance');

        expect(model.findById).lastCalledWith('I need some IT Assistance');
        expect(model.findById).toBeCalledTimes(1);
        expect(reply).toEqual({
            body: 'Yes, please.',
            ticket: '6402697307e6d1fcd1d3146d',
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

        fail('Reply not found exception was not called.');
    });

    it('should update an existing reply', async () => {
        const replyData = {
            body: 'Yes, please.',
            ticket: '6402697307e6d1fcd1d3146d',
            user: '63fe86f14f07c7e05f7db502'
        };

        jest.spyOn(model, 'findByIdAndUpdate')
            .mockImplementation((filter: any) => {
                return {
                    exec: jest.fn().mockResolvedValue(replyData),
                } as any;
            });

        const reply = await repository.update('fake-reply-id', replyData);

        expect(reply).toStrictEqual(replyData);
        expect(model.findByIdAndUpdate).toBeCalledTimes(1);
        expect(model.findByIdAndUpdate).lastCalledWith(
            'fake-reply-id',
            replyData,
            { new: true }
        );
    });

    it('should delete an existing reply', async () => {
        jest.spyOn(model, 'findByIdAndDelete')
            .mockImplementation((filter: any) => {
                return { exec: jest.fn() } as any;
            });

        const reply = await repository.remove('fake-reply-id');

        expect(model.findByIdAndDelete).toBeCalledTimes(1);
        expect(model.findByIdAndDelete).lastCalledWith('fake-reply-id');
    });
});
