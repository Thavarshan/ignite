import DB from '@/database/enums/db.enum';
import Connection from '@/database/connection';
import PasswordResetRequestSchema from '@/auth/schemas/password-reset-request.schema';
import PasswordEnum from '@/auth/enums/password.enum';
import { Provider } from '@nestjs/common';
import PasswordResetRequest from '@/auth/models/password-reset-request.model';

const authProvider: Provider[] = [
    {
        provide: PasswordEnum.MODEL,

        useFactory: (connection: Connection) => {
            const model = new PasswordResetRequest(
                connection,
                PasswordResetRequestSchema
            );

            return model.newInstance();
        },

        inject: [DB.KEY],
    }
];

export default authProvider;
