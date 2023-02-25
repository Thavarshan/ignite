import DB from '@/database/enums/db.enum';
import Connection from '@/database/connection';
import TeamSchema from '@/teams/schemas/team.schema';
import TeamEnum from '@/teams/enums/team.enum';
import { Provider } from '@nestjs/common';
import Team from '@/teams/models/team.model';

const teamsProvider: Provider[] = [
    {
        provide: TeamEnum.MODEL,

        useFactory: (connection: Connection) => {
            const model = new Team(connection, TeamSchema);

            return model.newInstance();
        },

        inject: [DB.KEY],
    }
];

export default teamsProvider;
