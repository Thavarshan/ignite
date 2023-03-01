import { Test, TestingModule } from '@nestjs/testing';
import PasswordService from './password.service';
import UserRepository from '../../users/repositories/user.repository';
import PasswordEnum from '../../auth/enums/password.enum';
import { Model } from 'mongoose';
import PasswordResetRequest from '../contracts/password-reset-request.contract';
import { randomUUID } from 'crypto';
import mockModel from '../../users/models/mock.model';

describe('PasswordService', () => {
    let service: PasswordService;
    let repository: UserRepository;
    let model: Model<PasswordResetRequest>;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                PasswordService,
                {
                    provide: UserRepository,
                    useValue: {
                        new: jest.fn(),
                        constructor: jest.fn(),
                        findByEmail: jest.fn()
                    }
                },
                {
                    provide: PasswordEnum.MODEL,
                    useValue: {
                        new: jest.fn(),
                        constructor: jest.fn(),
                        create: jest.fn()
                    }
                }
            ],
        }).compile();

        service = module.get<PasswordService>(PasswordService);
        repository = module.get<UserRepository>(UserRepository);
        model = module.get<Model<PasswordResetRequest>>(PasswordEnum.MODEL);
    });

    it('should be defined', () => {
        expect(service).toBeDefined();
    });

    it('should generate password reset request', async () => {
        const token = randomUUID();

        jest.spyOn(model, 'create')
            .mockImplementation(() => Promise.resolve({
                email: 'weston@jayz.com', token
            }));
        jest.spyOn(repository, 'findByEmail');

        await service.sendResetRequestLink({ email: 'weston@jayz.com' });

        expect(model.create).toBeCalledTimes(1);
        expect(repository.findByEmail).toBeCalledTimes(1);
    });

    it('should reset user password', async () => {
        const password = randomUUID();

        jest.spyOn(repository, 'findByEmail')
            .mockImplementation(() => {
                mockModel.password = 'previous-password';

                return Promise.resolve({
                    save: jest.fn()
                } as any);
            });

        await service.reset({
            password,
            passwordConfirmation: password,
            email: 'weston@jayz.com'
        });

        expect(repository.findByEmail).toBeCalledTimes(1);
    });
});
