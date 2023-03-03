import { Test, TestingModule } from '@nestjs/testing';
import PasswordEnum from '../enums/password.enum';
import PasswordResetRequest from '../models/password-reset-request.model';
import PasswordResetRequestContract from '../contracts/password-reset-request.contract';
import authProvider from './auth.provider';
import DB from '../../database/enums/db.enum';
import { Model } from 'mongoose';

describe('AuthProvider', () => {
    let model: PasswordResetRequest;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                ...authProvider,
                {
                    provide: DB.KEY,
                    useValue: {
                        model: jest.fn()
                            .mockReturnValue(
                                {} as Model<PasswordResetRequestContract>
                            ),
                    },
                },
            ],
        }).compile();

        model = module.get<PasswordResetRequest>(PasswordEnum.MODEL);
    });

    it('PASSWORD_RESET_REQUEST_MODEL should be defined', () => {
        expect(model).toBeDefined();
    });
});
