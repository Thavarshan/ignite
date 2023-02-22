import { Test, TestingModule } from '@nestjs/testing';
import UsersProvider from './users.provider';
import DB from '../../database/enums/db.enum';
import UserEnum from '../enums/user.enum';
import User from '../models/user.model';
import { Model } from 'mongoose';

describe('UsersProvider', () => {
    let model: User;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                ...UsersProvider,
                {
                    provide: DB.KEY,
                    useValue: {
                        model: jest
                            .fn()
                            .mockReturnValue({} as Model<User>),
                    },
                },
            ],
        }).compile();

        model = module.get<User>(UserEnum.MODEL);
    });

    it('USER_MODEL should be defined', () => {
        expect(model).toBeDefined();
    });
});
