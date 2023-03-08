import { Test, TestingModule } from '@nestjs/testing';
import RepliesService from '../services/replies.service';
import RepliesController from './replies.controller';

describe('RepliesController', () => {
    let service: RepliesService;
    let controller: RepliesController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [RepliesController],
            providers: [
                {
                    provide: RepliesService,
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

        service = module.get<RepliesService>(RepliesService);
        controller = module.get<RepliesController>(RepliesController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });

    it('should create a new reply', async () => {
        jest.spyOn(service, 'create').mockImplementationOnce(() =>
            Promise.resolve({
                body: 'Thanks for the assistance',
                ticket: '12ugli23g1983201',
                user: '127893hypr78q0rp94ejde'
            } as any),
        );

        const replyData = {
            body: 'Thanks for the assistance',
            ticket: '12ugli23g1983201',
            user: '127893hypr78q0rp94ejde'
        };
        const reply = await controller.create(replyData);

        expect(reply).toStrictEqual(replyData);
    });

    it('findOne should return reply', async () => {
        jest.spyOn(service, 'findOne')
            .mockImplementationOnce(() => {
                return {
                    body: 'Thanks for the assistance',
                    ticket: '12ugli23g1983201',
                    user: '127893hypr78q0rp94ejde'
                } as any;
            });

        const reply = await controller.findOne('IT Assistance');

        expect(service.findOne).toBeCalledTimes(1);
        expect(service.findOne).lastCalledWith('IT Assistance');
        expect(reply).toEqual({
            body: 'Thanks for the assistance',
            ticket: '12ugli23g1983201',
            user: '127893hypr78q0rp94ejde'
        });
    });

    it('findAll should return reply', async () => {
        jest.spyOn(service, 'findAll')
            .mockImplementationOnce(() => {
                return {
                    data: [{
                        body: 'Thanks for the assistance',
                        ticket: '12ugli23g1983201',
                        user: '127893hypr78q0rp94ejde'
                    }]
                } as any;
            });

        const replys = await controller.findAll();

        expect(service.findAll).toBeCalledTimes(1);
        expect(replys).toEqual({
            data: [{
                body: 'Thanks for the assistance',
                ticket: '12ugli23g1983201',
                user: '127893hypr78q0rp94ejde'
            }]
        });
    });

    it('should update an existing reply', async () => {
        const replyData = {
            body: 'Thanks for the assistance',
            ticket: '12ugli23g1983201',
            user: '127893hypr78q0rp94ejde'
        };

        jest.spyOn(service, 'update')
            .mockImplementationOnce(() => {
                return replyData as any;
            });

        const reply = await controller.update('IT Assistance', replyData);

        expect(reply).toStrictEqual(replyData);
        expect(service.update).toBeCalledTimes(1);
        expect(service.update).lastCalledWith('IT Assistance', replyData);
    });

    it('should delete an existing reply', async () => {
        jest.spyOn(service, 'remove')
            .mockImplementationOnce(() => {
                return null as any;
            });

        await controller.remove('IT Assistance');

        expect(service.remove).toBeCalledTimes(1);
        expect(service.remove).lastCalledWith('IT Assistance');
    });
});
