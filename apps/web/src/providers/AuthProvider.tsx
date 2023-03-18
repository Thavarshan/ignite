import AuthContext from '@/contexts/auth';
import Credentials from '@/lib/Contracts/Auth/Credentials';
import AuthProviderContract from '@/lib/Contracts/Auth/AuthProvider';
import User from '@/lib/Contracts/Auth/User';
import { apiUrl } from '@/lib/Utils/url';
import { deleteCookie, getCookie, setCookie } from 'cookies-next';
import React, { useEffect, useState } from 'react';
import router from 'next/router';
import ErrorResponse from '@/lib/Contracts/Http/Responses/ErrorResponse';
import Client from '@/lib/Api/Client';

const AuthProvider = ({ children }: AuthProviderContract) => {
    const [user, setUser] = useState<User | null>(null);
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [accessToken, setAccessToken] = useState<string | null>(null);
    const [refreshToken, setRefreshToken] = useState<string | null>(null);

    useEffect(() => {
        if (!user) {
            const user = localStorage.getItem('user');

            if (user != null && user !== 'undefined') {
                setUser(JSON.parse(user) as User);

                setIsLoggedIn(true);
            }
        }
    }, [user]);

    useEffect(() => {
        if (!accessToken) {
            const token = getCookie('token');

            setAccessToken((token ? token : null) as any);
        }
    }, [accessToken]);

    useEffect(() => {
        if (!refreshToken) {
            const token = localStorage.getItem('refreshToken');

            if (token != null && token !== 'undefined') {
                setRefreshToken(token);
            }
        }
    }, [refreshToken]);

    const getUser = async (token: string): Promise<User> => {
        const response = await fetch(apiUrl('/user'), {
            headers: { Authorization: `Bearer ${token}` }
        });

        if (!response.ok) {
            const errorData: ErrorResponse = await response.json();

            throw new Error(errorData.message as string);
        }

        const user = await response.json();

        return user;
    };

    const login = async (credentials: Credentials) => {
        const token = getCookie('token');
        const client = new Client(token as string);
        const accessToken = await client.authenticate(credentials);

        setCookie('token', accessToken, { sameSite: true });

        const user = await getUser(accessToken);

        setUser(user);

        localStorage.setItem('user', JSON.stringify(user));

        setIsLoggedIn(true);
    };

    const logout = async (): Promise<void> => {
        deleteCookie('token');
        setUser(null);
        setIsLoggedIn(false);
        setAccessToken('');
        setRefreshToken('');

        localStorage.removeItem('refreshToken');
        localStorage.removeItem('user');

        router.push('/');
    };

    return (
        <AuthContext.Provider value={{
            isLoggedIn,
            user,
            refreshToken,
            accessToken,
            login,
            logout
        }}>
            {children}
        </AuthContext.Provider>
    );
};

export default AuthProvider;
