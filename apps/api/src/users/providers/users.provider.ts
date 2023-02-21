import Connection from '@/database/connection';
import DB from '@/database/enums/db.enum';
import User from '@/users/models/user.model';
import UserSchema from '@/users/schemas/user.schema';
import UserEnum from '@/users/enums/user.enum';

export default {
    provide: UserEnum.MODEL,

    useFactory: (connection: Connection) => {
        const model = new User(connection, UserSchema);

        return model.newInstance();
    },

    inject: [DB.KEY],
};
