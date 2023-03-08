import Layout from '@/layouts/app';
import axios from '@/utils/axios';
import {
    Text,
    Stack,
    LinkBox,
    Box,
    LinkOverlay,
    HStack,
    Badge
} from '@chakra-ui/react';
import { GetServerSideProps, NextPage } from 'next';
import Head from 'next/head';
import Link from 'next/link';
import moment from 'moment';

const Index = ({ tickets }: any) => {
    return (
        <>
            <Head>
                <title>Tickets | Ignite</title>
            </Head>

            <Stack spacing='2'>
                {tickets.map((ticket: any) => (
                    <LinkBox key={ticket.id} className='cursor-pointer' as='article' maxW='2xl' px='4' py='3' _hover={{ bg: 'gray.50' }} rounded='xl'>
                        <Link href={`/tickets/${ticket.id}`}>
                            <Box as='time' dateTime={ticket.createdAt} fontSize='xs'>
                                Opened <span className='text-blue-500'>{moment(ticket.createdAt).fromNow()}</span> by <span className='font-bold'>{ticket.user.name}</span>
                            </Box>

                            <Text my='2' fontWeight='bold' fontSize='sm' color='gray.800'>
                                <LinkOverlay href='#'>{ticket.title}</LinkOverlay>
                            </Text>

                            <Text fontSize='sm' maxW='lg' color='gray.500'>{ticket.body}</Text>

                            <HStack mt='4' spacing='2'>
                                <Badge rounded='md' px='2' fontSize='xs' variant='subtle' colorScheme='green'>Open</Badge>
                                <Badge rounded='md' px='2' fontSize='xs'>{ticket.channel.name}</Badge>
                                {/* <Badge rounded='md' px='2' fontSize='xs'>{ticket.replies.length}</Badge> */}
                            </HStack>
                        </Link>
                    </LinkBox>
                ))}
            </Stack>
        </>
    );
};

Index.getLayout = function getLayout (page: NextPage) {
    return (
        <Layout>{page}</Layout>
    );
};

export const getServerSideProps: GetServerSideProps = async () => {
    const { data: { data: tickets } } = await axios.get('/tickets');

    return { props: { tickets } };
};


export default Index;
