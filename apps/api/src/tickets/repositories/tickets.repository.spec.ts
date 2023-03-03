import { Test, TestingModule } from '@nestjs/testing';
import TicketsRepository from './tickets.repository';

describe('TicketsRepository', () => {
    let service: TicketsRepository;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [TicketsRepository],
        }).compile();

        service = module.get<TicketsRepository>(TicketsRepository);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });
});
