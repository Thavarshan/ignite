import { v4 } from 'uuid';

class User {
    constructor (
        public name: string,
        public email: string,
        public password: string,
        public phone?: string,
        public verification: string = v4(),
        public verified: boolean = false,
        public verificationExpires: Date = new Date(),
        public loginAttempts?: number,
        public blockedAt?: Date
    ) { }

    public async save (): Promise<void> {
        //
    }
}

const user = new User(
    'Wade Solman',
    'ws@wxample.com',
    '$2a$08$9qx5nogMjutjDU7PdyXO.eCjwWZuEs63Yx0iyCpIAKkOa5CIW4sKm', // password
    '1234567890'
);

export default user;
