import { verify } from '../Utils/jwt';

export default abstract class Guard {
    public abstract authorize (_token?: string): Promise<boolean>;

    protected async verifyToken (token: string): Promise<boolean> {
        const decoded = await verify(token, process.env.NEXT_PUBLIC_API_KEY as string);

        if (Date.now() >= (decoded?.exp || 0) * 1000) {
            return false;
        }

        return true;
    }
}
