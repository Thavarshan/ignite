import AuthContext from '@/contexts/auth';
import Credentials from '@/contracts/auth/Credentials';
import AuthProviderContract from '@/contracts/auth/AuthProvider';
import User from '@/contracts/auth/User';
import { apiUrl } from '@/utils/url';
import { deleteCookie, getCookie, setCookie } from 'cookies-next';
import React, { useEffect, useState } from 'react';
import router from 'next/router';

const AuthProvider = ({ children }: AuthProviderContract) => {
    const [user, setUser] = useState<User | null>(null);
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [accessToken, setAccessToken] = useState<string | null>(null);
    const [refreshToken, setRefreshToken] = useState<string | null>(null);
    const [errors, setErrors] = useState<string | string[] | null>(null);

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

    const getUser = async (token: string): Promise<User | undefined> => {
        const response = await fetch(apiUrl('/user'), {
            headers: { Authorization: `Bearer ${token}` }
        });

        if (response.ok) {
            const user = await response.json();

            return user;
        }

        setErrors(await response.json());
    };

    const login = async (credentials: Credentials) => {
        const response = await fetch(apiUrl('/login'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(credentials)
        });

        if (response.ok) {
            const { accessToken } = await response.json();

            setCookie('token', accessToken, { sameSite: true });

            const user = await getUser(accessToken);

            if (!user) {
                throw new Error('User access unauthorized.');
            }

            setUser(user);

            localStorage.setItem('user', JSON.stringify(user));

            setIsLoggedIn(true);
        } else {
            setErrors(await response.json());
        }
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
            errors,
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
