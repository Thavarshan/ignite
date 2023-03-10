import Credentials from '../auth/Credentials';
import User from '../auth/User';

export default interface AuthContext {
    isLoggedIn: boolean;
    user: User | null;
    errors: string | string[] | null;
    accessToken: string | null;
    refreshToken: string | null;
    login: (_credentials: Credentials) => Promise<void>;
    logout: () => Promise<void>;
    // refresh: () => Promise<void>;
}
