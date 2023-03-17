import Client from '@/lib/Api/Client';
import ClientContract from '@/lib/Contracts/Api/Client';
import { getCookie } from 'cookies-next';
import { useEffect, useState } from 'react';

const useApi = () => {
    const [client, setClient] = useState<ClientContract | undefined>(undefined);

    useEffect(() => {
        const instance = new Client(getCookie('token') as string);

        setClient(instance);
    }, [client]);

    const getUsers = async () => {
        return await client?.resources('users').all();
    };

    const createUser = async (data: { [key: string]: any; }) => {
        return await client?.resources('register').create(data);
    };

    return {
        client,
        getUsers,
        createUser
    };
};

export default useApi;
