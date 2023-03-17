import Guard from './Guard';

export default class AuthGuard extends Guard {
    public async authorize (token?: string): Promise<boolean> {
        if (!token) {
            return false;
        }

        return await this.verifyToken(token);
    }
}
