import { Test, TestingModule } from '@nestjs/testing';
import TeamsProvider from './teams.provider';
import DB from '../../database/enums/db.enum';
import TeamEnum from '../enums/team.enum';
import Team from '../models/team.model';
import { Model } from 'mongoose';

describe('TeamsProvider', () => {
    let model: Team;

    beforeEach(async () => {
        const module: TestingModule = await Test.createTestingModule({
            providers: [
                ...TeamsProvider,
                {
                    provide: DB.KEY,
                    useValue: {
                        model: jest
                            .fn()
                            .mockReturnValue({} as Model<Team>),
                    },
                },
            ],
        }).compile();

        model = module.get<Team>(TeamEnum.MODEL);
    });

    it('USER_MODEL should be defined', () => {
        expect(model).toBeDefined();
    });
});
