/* eslint-disable react-hooks/exhaustive-deps */
import { useEffect } from 'react';
import Router from 'next/router';
import useSWR from 'swr';
import axios from '@/utils/axios';
import { deleteCookie, getCookie } from 'cookies-next';

const useAuth = ({
    middleware,
    redirectIfAuthenticated
}: any = {}) => {
    const { data: user, error, mutate } = useSWR<any>('/api/user', async () => {
        const token = getCookie('token');

        return axios.get('/user', { headers: { Authorization: `Bearer ${token}` } })
            .then(response => response.data)
            .catch(error => { throw error; });
    });

    const logout = async () => {
        deleteCookie('token');

        mutate();

        Router.push('/auth/login');
    };

    useEffect(() => {
        if (middleware === 'guest' && redirectIfAuthenticated && user) {
            Router.push(redirectIfAuthenticated);
        }

        if (middleware === 'auth' && error) logout();
    }, [user, error]);

    return {
        user,
        logout,
        mutate
    };
};

export default useAuth;
