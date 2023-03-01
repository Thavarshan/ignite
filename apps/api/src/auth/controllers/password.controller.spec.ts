import { Test, TestingModule } from '@nestjs/testing';
import PasswordController from './password.controller';
import PasswordService from '../services/password.service';

describe('PasswordController', () => {
    let service: PasswordService;
    let controller: PasswordController;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            controllers: [PasswordController],
            providers: [{
                provide: PasswordService,
                useValue: {
                    new: jest.fn(),
                    constructor: jest.fn(),
                    reset: jest.fn(),
                    sendResetRequestLink: jest.fn(),
                }
            }]
        }).compile();

        service = module.get<PasswordService>(PasswordService);
        controller = module.get<PasswordController>(PasswordController);
    });

    it('should be defined', () => {
        expect(controller).toBeDefined();
    });

    it('should reset user password', async () => {
        jest.spyOn(service, 'sendResetRequestLink')
            .mockImplementationOnce(() => Promise.resolve(undefined));

        await controller.sendResetRequestLink({
            email: 'weston@james.com'
        });
    });

    it('should reset user password', async () => {
        jest.spyOn(service, 'reset')
            .mockImplementationOnce(() => Promise.resolve(undefined));

        const dto = {
            password: 'newSecretPassword',
            passwordConfirmation: 'newSecretPassword',
            email: 'weston@james.com'
        };

        await controller.resetPassword(dto);
    });
});
