import { v4 } from 'uuid';
import Roles from '../enums/roles.enum';

class User {
    constructor (
        public name: string,
        public username: string,
        public email: string,
        public password: string,
        public phone?: string,
        public verification: string = v4(),
        public verified: boolean = false,
        public verificationExpires: Date = new Date(),
        public loginAttempts?: number,
        public blockedAt?: Date,
        public role: Roles.USER | Roles.AGENT | Roles.ADMIN = Roles.USER
    ) { }

    public async save (): Promise<void> {
        //
    }
}

const user = new User(
    'Wade Solman',
    'WadeSolman',
    'ws@wxample.com',
    '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
    '1234567890',
    Roles.USER
);

export default user;
