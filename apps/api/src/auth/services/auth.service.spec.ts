import { Test, TestingModule } from '@nestjs/testing';
import AuthService from './auth.service';
import LocalAuth from '../authenticators/local.auth';
import UserRepository from '../../users/repositories/user.repository';
import mockModel from '../../users/models/mock.model';
import UserEnum from '../../users/enums/user.enum';
import { JwtService } from '@nestjs/jwt';

describe('AuthService', () => {
    let service: AuthService;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                JwtService,
                AuthService,
                LocalAuth,
                UserRepository,
                {
                    provide: UserEnum.MODEL,
                    useValue: {
                        new: jest.fn().mockResolvedValue(mockModel),
                        constructor: jest.fn().mockResolvedValue(mockModel),
                    }
                }
            ],
        }).compile();

        service = module.get<AuthService>(AuthService);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });
});
