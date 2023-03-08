/* eslint-disable @typescript-eslint/no-unused-vars */
import { Test, TestingModule } from '@nestjs/testing';
import ChannelsRepository from './channels.repository';
import { Model } from 'mongoose';
import Channel from '../contracts/channel.contract';
import ChannelEnum from '../enums/channel.enum';
import UsersRepository from '../../users/repositories/users.repository';
import { fail } from 'assert';

describe('ChannelsRepository', () => {
    let repository: ChannelsRepository;
    let model: Model<Channel>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                ChannelsRepository,
                {
                    provide: ChannelEnum.MODEL,
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

        repository = module.get<ChannelsRepository>(ChannelsRepository);
        model = module.get<Model<Channel>>(ChannelEnum.MODEL);
    });

    it('should be defined', () => {
        expect(repository).toBeDefined();
    });

    it('should create a new channel', async () => {
        jest.spyOn(model, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                name: 'IT Assistance',
                description: 'Help related to computer matters.',
                slug: 'it-assistance'
            } as any),
        );

        const channelData = {
            name: 'IT Assistance',
            description: 'Help related to computer matters.',
            slug: 'it-assistance'
        };
        const channel = await repository.create(channelData);

        expect(channel).toStrictEqual(channelData);
    });

    it('findAll should return a list of channels', async () => {
        jest.spyOn(model, 'find').mockImplementation(() => {
            return {
                exec: jest.fn().mockImplementation(() => ([{
                    name: 'IT Assistance',
                    description: 'Help related to computer matters.',
                    slug: 'it-assistance'
                }]))
            } as any;
        });

        const channel = await repository.findAll();

        expect(model.find).toBeCalledTimes(1);
        expect(channel).toEqual([{
            name: 'IT Assistance',
            description: 'Help related to computer matters.',
            slug: 'it-assistance'
        }]);
    });

    it('findBySlug should return channel', async () => {
        jest.spyOn(model, 'findOne').mockImplementation(() => {
            return {
                exec: jest.fn().mockImplementation(() => ({
                    name: 'IT Assistance',
                    description: 'Help related to computer matters.',
                    slug: 'it-assistance'
                }))
            } as any;
        });

        const channel = await repository.findBySlug('it-assistance');

        expect(model.findOne).toBeCalledTimes(1);
        expect(channel).toEqual({
            name: 'IT Assistance',
            description: 'Help related to computer matters.',
            slug: 'it-assistance'
        });
        expect(model.findOne).lastCalledWith({ slug: 'it-assistance' });
    });

    it('return one result', async () => {
        jest.spyOn(model, 'findById').mockImplementation(() => {
            return {
                exec: jest.fn().mockImplementation(() => ({
                    name: 'IT Assistance',
                    description: 'Help related to computer matters.',
                    slug: 'it-assistance'
                }))
            } as any;
        });

        const channel = await repository.findById('fake-channel-id');

        expect(model.findById).lastCalledWith('fake-channel-id');
        expect(model.findById).toBeCalledTimes(1);
        expect(channel).toEqual({
            name: 'IT Assistance',
            description: 'Help related to computer matters.',
            slug: 'it-assistance'
        });
    });

    it('return a null result', async () => {
        jest.spyOn(model, 'findById')
            .mockImplementation((filter: any, callback: any) => {
                return {
                    exec: jest.fn().mockImplementation(() => {
                        throw new Error;
                    }),
                } as any;
            });

        try {
            await repository.findById('Private');
        } catch (e) {
            expect(e).toBeDefined();

            return;
        }

        fail('Channel not found exception was not called.');
    });

    it('should update an existing channel', async () => {
        const channelData = {
            name: 'IT Assistance',
            description: 'Help related to computer matters.',
            slug: 'it-assistance'
        };

        jest.spyOn(model, 'findByIdAndUpdate')
            .mockImplementation((filter: any) => {
                return {
                    exec: jest.fn().mockResolvedValue(channelData),
                } as any;
            });

        const channel = await repository.update('fake-channel-id', channelData);

        expect(channel).toStrictEqual(channelData);
        expect(model.findByIdAndUpdate).toBeCalledTimes(1);
        expect(model.findByIdAndUpdate).lastCalledWith(
            'fake-channel-id',
            channelData,
            { new: true }
        );
    });

    it('should delete an existing channel', async () => {
        jest.spyOn(model, 'findByIdAndDelete')
            .mockImplementation((filter: any) => {
                return { exec: jest.fn() } as any;
            });

        const channel = await repository.remove('fake-channel-id');

        expect(model.findByIdAndDelete).toBeCalledTimes(1);
        expect(model.findByIdAndDelete).lastCalledWith('fake-channel-id');
    });
});
