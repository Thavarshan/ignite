import Layout from '@/layouts/app';
import Client from '@/lib/Api/Client';
// import {
//     Box,
//     Button,
//     chakra,
//     Text,
//     GridItem,
//     SimpleGrid,
//     Stack,
//     Heading,
//     FormControl,
//     FormLabel,
//     Input,
//     Select
// } from '@chakra-ui/react';
import { getCookie } from 'cookies-next';
import { GetServerSideProps, NextPage } from 'next';

const Show = ({ user }: any) => {
    return (
        <>
            {user.name}
        </>
    );
};

export const getServerSideProps: GetServerSideProps = async ({ req, res, params }) => {
    const token = getCookie('token', { req, res });
    const client = new Client(token as string);
    const user = await client
        .resources('users')
        .get(params?.username as string);

    return { props: { user } };
};

Show.getLayout = function getLayout (page: NextPage) {
    return (<Layout>{page}</Layout>);
};

export default Show;
