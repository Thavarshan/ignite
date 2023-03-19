import Layout from '@/layouts/app';
import Client from '@/lib/Api/Client';
import { AddIcon } from '@chakra-ui/icons';
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
    Text,
    Flex,
    Spacer,
    ButtonGroup,
    Button,
    IconButton
} from '@chakra-ui/react';
import { getCookie } from 'cookies-next';
import moment from 'moment';
import { GetServerSideProps, NextPage } from 'next';
import Head from 'next/head';
import { FaFilter } from 'react-icons/fa';

const Index = ({ tickets }: any) => {
    return (
        <>
            <Head>
                <title>Tickets | Ignite</title>
            </Head>

            <Box>
                <Flex align='center'>
                    <Heading as='h4' size='md'>Tickets</Heading>
                    <Spacer />
                    <ButtonGroup size='sm' isAttached variant='outline'>
                        <Button leftIcon={<FaFilter />}>Filter</Button>
                        <IconButton aria-label='Add to friends' icon={<AddIcon />} />
                    </ButtonGroup>
                </Flex>
            </Box>

            {tickets.length > 0 ? <Box mt='6' boxShadow='sm' bg='white' rounded='xl' border='1px' borderColor='gray.100'>
                <Table variant='simple'>
                    <Thead>
                        <Tr bg='gray.50'>
                            <Th>Name</Th>
                            <Th>Username</Th>
                            <Th>Email</Th>
                            <Th>Role</Th>
                            <Th isNumeric>Joined</Th>
                        </Tr>
                    </Thead>

                    <Tbody>
                        {tickets && tickets.map((ticket: any) => (
                            <Tr key={ticket.id}>
                                <Td>
                                    <Box fontWeight='semibold' fontSize='sm'>{ticket.name}</Box>
                                    <Box mt='1' fontSize='sm'><Badge rounded='md' fontWeight='medium' color='gray.600'>{ticket.id}</Badge></Box>
                                </Td>
                                <Td>
                                    <Tag colorScheme='blue' rounded='md'>
                                        <TagLabel>{ticket.ticketname}</TagLabel>
                                    </Tag>
                                </Td>
                                <Td>
                                    <Link color='blue.500' href={`mailto:${ticket.email}`} isExternal>
                                        {ticket.email}
                                    </Link>
                                </Td>
                                <Td><Text textTransform='capitalize'>{ticket.role}</Text></Td>
                                <Td isNumeric>
                                    <Box fontSize='sm'>{moment(ticket.createdAt).fromNow()}</Box>
                                    <Box fontSize='xs' color='gray.500'>{moment(ticket.createdAt).format('ll')}</Box>
                                </Td>
                            </Tr>
                        ))}
                    </Tbody>
                </Table>
            </Box> : <Text mt='6' color='gray.500' fontSize='sm'>No tickets found.</Text>}
        </>
    );
};

export const getServerSideProps: GetServerSideProps = async ({ req, res }) => {
    const token = getCookie('token', { req, res });
    const client = new Client(token as string);
    const tickets = await client.resources('tickets').all();

    return { props: { tickets } };
};

Index.getLayout = function getLayout (page: NextPage) {
    return (<Layout>{page}</Layout>);
};

export default Index;
