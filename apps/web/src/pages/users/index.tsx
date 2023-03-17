import Layout from '@/layouts/app';
import Client from '@/lib/Api/Client';
import {
    Table,
    Thead,
    Tbody,
    Tr,
    Th,
    Td,
    Box,
    Badge,
    Tag,
    TagLabel,
    Link,
    Heading,
    Text
} from '@chakra-ui/react';
import { getCookie } from 'cookies-next';
import moment from 'moment';
import { GetServerSideProps, NextPage } from 'next';
import Head from 'next/head';

const Index = ({ users }: any) => {
    return (
        <>
            <Head>
                <title>Users | Ignite</title>
            </Head>

            <Box>
                <Heading as='h4' size='md'>Users</Heading>
            </Box>

            <Box mt='6' boxShadow='sm' bg='white' rounded='xl' border='1px' borderColor='gray.100'>
                <Table variant='simple'>
                    <Thead>
                        <Tr>
                            <Th>Name</Th>
                            <Th>Username</Th>
                            <Th>Email</Th>
                            <Th>Role</Th>
                            <Th isNumeric>Joined</Th>
                        </Tr>
                    </Thead>

                    <Tbody>
                        {users && users.map((user: any) => (
                            <Tr key={user.id}>
                                <Td>
                                    <Box fontWeight='semibold' fontSize='sm'>{user.name}</Box>
                                    <Box mt='1' fontSize='sm'><Badge rounded='md' fontWeight='medium' color='gray.600'>{user.id}</Badge></Box>
                                </Td>
                                <Td>
                                    <Tag colorScheme='blue' rounded='md'>
                                        <TagLabel>{user.username}</TagLabel>
                                    </Tag>
                                </Td>
                                <Td>
                                    <Link color='blue.500' href={`mailto:${user.email}`} isExternal>
                                        {user.email}
                                    </Link>
                                </Td>
                                <Td><Text textTransform='capitalize'>{user.roles[0]}</Text></Td>
                                <Td isNumeric>
                                    <Box fontSize='sm'>{moment(user.createdAt).fromNow()}</Box>
                                    <Box fontSize='xs' color='gray.500'>{moment(user.createdAt).format('ll')}</Box>
                                </Td>
                            </Tr>
                        ))}
                    </Tbody>
                </Table>
            </Box>
        </>
    );
};

export const getServerSideProps: GetServerSideProps = async ({ req, res }) => {
    const token = getCookie('token', { req, res });
    const client = new Client(token as string);
    const users = await client.resources('users').all();

    return { props: { users } };
};

Index.getLayout = function getLayout (page: NextPage) {
    return (<Layout>{page}</Layout>);
};

export default Index;
