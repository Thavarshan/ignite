import Guard from './Guard';

export default class GuestGuard extends Guard {
    public async authorize (token?: string): Promise<boolean> {
        if (token) {
            const verification = await this.verifyToken(token);

            return !verification;
        }

        return true;
    }
}
