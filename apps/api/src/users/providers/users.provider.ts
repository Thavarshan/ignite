import DB from '@/database/enums/db.enum';
import Connection from '@/database/connection';
import UserSchema from '@/users/schemas/user.schema';
import UserEnum from '@/users/enums/user.enum';
import { Provider } from '@nestjs/common';
import User from '../models/user.model';

const usersProvider: Provider[] = [
    {
        provide: UserEnum.MODEL,

        useFactory: (connection: Connection) => {
            const model = new User(connection, UserSchema);

            return model.newInstance();
        },

        inject: [DB.KEY],
    }
];

export default usersProvider;
