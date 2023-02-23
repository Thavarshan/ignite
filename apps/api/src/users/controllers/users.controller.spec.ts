import { Test, TestingModule } from '@nestjs/testing';
import UsersController from './users.controller';
import UserService from '../services/user.service';
import UserRepository from '../repositories/user.repository';
import UserEnum from '../enums/user.enum';
import mockModel from '../models/mock.model';

describe('UsersController', () => {
    let controller: UsersController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [UsersController],
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
                        exists: jest.fn(),
                        exec: jest.fn(),
                    }
                }
            ],
        }).compile();

        controller = module.get<UsersController>(UsersController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });
});
