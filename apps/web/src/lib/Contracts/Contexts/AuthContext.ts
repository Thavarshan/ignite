import Credentials from '../Auth/Credentials';
import User from '../Auth/User';

export default interface AuthContext {
    isLoggedIn: boolean;
    user: User | null;
    accessToken: string | null;
    refreshToken: string | null;
    login: (_credentials: Credentials) => Promise<void>;
    logout: () => Promise<void>;
    // refresh: () => Promise<void>;
}
