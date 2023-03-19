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

const Index = ({ agents }: any) => {
    return (
        <>
            <Head>
                <title>Agents | Ignite</title>
            </Head>

            <Box>
                <Flex align='center'>
                    <Heading as='h4' size='md'>Agents</Heading>
                    <Spacer />
                    <ButtonGroup size='sm' isAttached variant='outline'>
                        <Button leftIcon={<FaFilter />}>Filter</Button>
                        <IconButton aria-label='Add to friends' icon={<AddIcon />} />
                    </ButtonGroup>
                </Flex>
            </Box>

            {agents.length > 0 ? <Box mt='6' boxShadow='sm' bg='white' rounded='xl' border='1px' borderColor='gray.100'>
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
                        {agents && agents.map((agent: any) => (
                            <Tr key={agent.id}>
                                <Td>
                                    <Box fontWeight='semibold' fontSize='sm'>{agent.name}</Box>
                                    <Box mt='1' fontSize='sm'><Badge rounded='md' fontWeight='medium' color='gray.600'>{agent.id}</Badge></Box>
                                </Td>
                                <Td>
                                    <Tag colorScheme='blue' rounded='md'>
                                        <TagLabel>{agent.agentname}</TagLabel>
                                    </Tag>
                                </Td>
                                <Td>
                                    <Link color='blue.500' href={`mailto:${agent.email}`} isExternal>
                                        {agent.email}
                                    </Link>
                                </Td>
                                <Td><Text textTransform='capitalize'>{agent.role}</Text></Td>
                                <Td isNumeric>
                                    <Box fontSize='sm'>{moment(agent.createdAt).fromNow()}</Box>
                                    <Box fontSize='xs' color='gray.500'>{moment(agent.createdAt).format('ll')}</Box>
                                </Td>
                            </Tr>
                        ))}
                    </Tbody>
                </Table>
            </Box> : <Text mt='6' color='gray.500' fontSize='sm'>No agents found.</Text>}
        </>
    );
};

export const getServerSideProps: GetServerSideProps = async ({ req, res }) => {
    const token = getCookie('token', { req, res });
    const client = new Client(token as string);
    const agents = await client.resources('users').all('role', 'agent');

    return { props: { agents } };
};

Index.getLayout = function getLayout (page: NextPage) {
    return (<Layout>{page}</Layout>);
};

export default Index;
