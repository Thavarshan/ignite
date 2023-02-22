import { Test, TestingModule } from '@nestjs/testing';
import UserService from './user.service';
import UserRepository from '../repositories/user.repository';
import UserEnum from '../enums/user.enum';
import mockModel from '../models/mock.model';

describe('UserService', () => {
    let service: UserService;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                UserService,
                UserRepository,
                {
                    provide: UserEnum.MODEL,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                        findOne: jest.fn(),
                        findById: jest.fn(),
                        create: jest.fn(),
                        findOneAndUpdate: jest.fn(),
                        deleteOne: jest.fn(),
                        exists: jest.fn(),
                        exec: jest.fn(),
                    }
                }
            ],
        }).compile();

        service = module.get<UserService>(UserService);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });
});
