import Base from '@/database/models/base.model';
import TeamContract from '@/teams/contracts/team.contract';
import { Injectable } from '@nestjs/common';

@Injectable()
export default class Team extends Base<TeamContract> {
    /**
     * The table associated with the model.
     *
     * @var {string}
     */
    protected readonly table: string = 'teams';
}
