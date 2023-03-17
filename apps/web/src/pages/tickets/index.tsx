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
import { getCookie } from 'cookies-next';
import Client from '@/lib/Api/Client';

const Index = ({ tickets }: any) => {
    return (
        <>
            <Head>
                <title>Tickets | Ignite</title>
            </Head>

            <Stack spacing='2'>
                {tickets.length > 0 ? tickets.map((ticket: any) => (
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
                )) : <Text color='gray.500' fontSize='sm'>No tickets found.</Text>}
            </Stack>
        </>
    );
};

Index.getLayout = function getLayout (page: NextPage) {
    return (<Layout>{page}</Layout>);
};

export const getServerSideProps: GetServerSideProps = async ({ req, res }) => {
    const token = getCookie('token', { req, res });
    const client = new Client(token as string);
    const tickets = await client.resources('tickets').all();

    return { props: { tickets } };
};

export default Index;
