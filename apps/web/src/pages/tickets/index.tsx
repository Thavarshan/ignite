import Layout from '@/layouts/app';
import {
    Text,
    Stack,
    Box,
    HStack,
    Tag,
    TagLeftIcon,
    TagLabel
} from '@chakra-ui/react';
import { GetServerSideProps, NextPage } from 'next';
import { HiOutlineChatAlt2 } from 'react-icons/hi';
import Head from 'next/head';
import Link from 'next/link';
import moment from 'moment';
import { apiUrl } from '@/utils/helpers';
import { getCookie } from 'cookies-next';

const Index = ({ tickets }: any) => {
    return (
        <>
            <Head>
                <title>Tickets | Ignite</title>
            </Head>

            <Stack spacing='2'>
                {tickets.map((ticket: any) => (
                    <Box key={ticket.id} className='cursor-pointer' maxW='2xl' px='4' py='3' _hover={{ bg: 'gray.50' }} rounded='xl'>
                        <Link href={`/tickets/${ticket.id}`} passHref>
                            <Box as='time' dateTime={ticket.createdAt} fontSize='xs'>
                                Opened <span className='text-blue-500'>{moment(ticket.createdAt).fromNow()}</span> by <span className='font-bold'>{ticket.user.name}</span>
                            </Box>

                            <Text my='2' fontWeight='bold' fontSize='sm' color='gray.800'>{ticket.title}</Text>

                            <Text fontSize='sm' maxW='lg' color='gray.500'>{ticket.body}</Text>

                            <HStack mt='4' spacing='2'>
                                <Tag size='sm' colorScheme='green'>Open</Tag>
                                <Tag size='sm'>{ticket.channel.name}</Tag>
                                <Tag size='sm' variant='ghost'>
                                    <TagLeftIcon boxSize='12px' as={HiOutlineChatAlt2} />
                                    <TagLabel>{ticket.replies.length}</TagLabel>
                                </Tag>
                                {/* <Badge rounded='md' px='2' fontSize='xs'>{ticket.replies.length}</Badge> */}
                            </HStack>
                        </Link>
                    </Box>
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

export const getServerSideProps: GetServerSideProps = async ({ req, res }) => {
    const token = getCookie('token', { req, res });

    const response = await fetch(apiUrl('/tickets'), {
        headers: { Authorization: `Bearer ${token}` }
    });

    if (!response.ok) {
        return {
            notFound: true
        };
    }

    const { data } = await response.json();

    return { props: { tickets: data } };
};

export default Index;
