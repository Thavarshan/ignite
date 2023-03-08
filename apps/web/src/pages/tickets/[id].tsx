import Layout from '@/layouts/app';
import axios from '@/utils/axios';
import {
    Text,
    Stack,
    Heading,
    Box
} from '@chakra-ui/react';
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

export const getServerSideProps: GetServerSideProps = async ({ params }: any) => {
    const { data: ticket } = await axios.get(`/tickets/${params.id}`);

    return { props: { ticket } };
};


export default Show;
