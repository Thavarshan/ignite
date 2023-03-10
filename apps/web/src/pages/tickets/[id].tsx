import Layout from '@/layouts/app';
import { apiUrl } from '@/utils/helpers';
import {
    Text,
    Stack,
    Heading,
    Box
} from '@chakra-ui/react';
import { getCookie } from 'cookies-next';
import { GetServerSideProps, NextPage } from 'next';
import Head from 'next/head';
// import moment from 'moment';

const Show = ({ ticket }: any) => {
    return (
        <>
            <Head>
                <title>Tickets | Ignite</title>
            </Head>

            <Stack direction='column' spacing='2'>
                <Box>
                    <Heading size='md'>{ticket.title}</Heading>

                    <Text mt='6' maxW='2xl'>This is the {ticket.body}</Text>
                </Box>
            </Stack>
        </>
    );
};

Show.getLayout = function getLayout (page: NextPage) {
    return (
        <Layout>{page}</Layout>
    );
};

export const getServerSideProps: GetServerSideProps = async ({ req, res, params }: any) => {
    const token = getCookie('token', { req, res });

    const response = await fetch(apiUrl(`/tickets/${params.id}`), {
        headers: { Authorization: `Bearer ${token}` }
    });

    if (!response.ok) {
        return { notFound: true };
    }

    const ticket = await response.json();

    return { props: { ticket } };
};


export default Show;
