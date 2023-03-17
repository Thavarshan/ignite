import AuthContextContract from '@/lib/Contracts/Contexts/AuthContext';
import { createContext } from 'react';

const AuthContext = createContext<AuthContextContract>({
    isLoggedIn: false,
    user: null,
    accessToken: null,
    refreshToken: null,
    login: () => Promise.resolve(),
    logout: () => Promise.resolve()
    // refresh: () => Promise.resolve()
});

export default AuthContext;
