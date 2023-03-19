import Layout from '@/layouts/app';
import Client from '@/lib/Api/Client';
import ChannelForm from '@/lib/Forms/Channel';
import setToastContent from '@/lib/Utils/toastConfig';
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
    Heading,
    Text,
    Flex,
    Spacer,
    ButtonGroup,
    Modal,
    ModalOverlay,
    ModalContent,
    ModalHeader,
    ModalBody,
    ModalCloseButton,
    IconButton,
    useDisclosure,
    Button,
    Stack,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Input,
    useToast,
    Textarea,
    Tag,
    TagLabel
} from '@chakra-ui/react';
import { getCookie } from 'cookies-next';
import { Field, Form, Formik } from 'formik';
import _ from 'lodash';
import moment from 'moment';
import { GetServerSideProps, NextPage } from 'next';
import Head from 'next/head';
import { useRouter } from 'next/router';
import { useState } from 'react';

const Index = ({ channels }: any) => {
    const [editable, setEditable] = useState({ name: '', description: '' });
    const toast = useToast();
    const router = useRouter();
    const {
        isOpen: createModalIsOpen,
        onOpen: createModalOnOpen,
        onClose: createModalOnClose
    } = useDisclosure();
    const {
        isOpen: editModalIsOpen,
        onOpen: editModalOnOpen,
        onClose: editModalOnClose
    } = useDisclosure();

    const refreshData = () => {
        router.replace(router.asPath);
    };

    const form = new ChannelForm();

    const editChannel = (channel: any) => {
        setEditable(channel);

        editModalOnOpen();
    };

    const closeEditModal = () => {
        editModalOnClose();

        setEditable({ name: '', description: '' });
    };

    const deleteChannel = async () => {
        await form.handleDelete(editable);

        closeEditModal();
    };

    return (
        <>
            <Head>
                <title>Channels | Ignite</title>
            </Head>

            <Box>
                <Flex align='center'>
                    <Heading as='h4' size='md'>Channels</Heading>
                    <Spacer />
                    <ButtonGroup size='sm' isAttached variant='outline'>
                        <IconButton onClick={createModalOnOpen} aria-label='Add to friends' icon={<AddIcon />} />
                    </ButtonGroup>
                </Flex>
            </Box>

            {
                channels.length > 0 ? <Box mt='6' boxShadow='sm' bg='white' rounded='xl' border='1px' borderColor='gray.100'>
                    <Table variant='simple'>
                        <Thead>
                            <Tr bg='gray.50'>
                                <Th>Name</Th>
                                <Th>Description</Th>
                                <Th>Slug</Th>
                                <Th isNumeric>Created</Th>
                            </Tr>
                        </Thead>

                        <Tbody>
                            {channels.map((channel: any) => (
                                <Tr key={channel.id}>
                                    <Td>
                                        <Box>
                                            <Button fontSize='sm' onClick={() => editChannel(channel)} fontWeight='semibold' color='gray.800' variant='link'>{channel.name}</Button>
                                        </Box>

                                        <Box mt='1' fontSize='sm'><Badge rounded='md' fontWeight='medium' color='gray.600'>{channel.id}</Badge></Box>
                                    </Td>
                                    <Td>
                                        <Text fontSize='sm' color='gray.500'>
                                            {channel.description}
                                        </Text>
                                    </Td>
                                    <Td>
                                        <Tag colorScheme='blue' rounded='md'>
                                            <TagLabel>{channel.slug}</TagLabel>
                                        </Tag>
                                    </Td>
                                    <Td isNumeric>
                                        <Box fontSize='sm'>{moment(channel.createdAt).fromNow()}</Box>
                                        <Box fontSize='xs' color='gray.500'>{moment(channel.createdAt).format('ll')}</Box>
                                    </Td>
                                </Tr>
                            ))}
                        </Tbody>
                    </Table>
                </Box > : <Text mt='6' color='gray.500' fontSize='sm'>No channels found.</Text>}

            {/* Create Channel Modal */}
            <Modal isOpen={createModalIsOpen} onClose={createModalOnClose}>
                <ModalOverlay />
                <ModalContent>
                    <ModalHeader>Create Channel</ModalHeader>
                    <ModalCloseButton />
                    <ModalBody pb='5'>
                        <Text fontSize='sm' color='gray.500'>
                            Channels provide a space for organized discussions. With forums, you can discuss specific topics and not worry about talking over each other
                        </Text>

                        <Box mt='6'>
                            <Formik
                                initialValues={{ name: '', description: '' }}
                                onSubmit={async (values, helpers) => {
                                    try {
                                        await form.handleCreateSubmit(values);

                                        refreshData();

                                        helpers.resetForm();
                                        helpers.setSubmitting(false);

                                        createModalOnClose();
                                    } catch (error: any) {
                                        error.message = JSON.parse(error.message);

                                        if (_.isArray(error.message)) {
                                            _.forEach(error.message, (message: string) => {
                                                toast(setToastContent('Channel creation failed', message));
                                            });
                                        } else {
                                            toast(setToastContent('Channel creation failed', error.message));
                                        }
                                    }

                                    helpers.setSubmitting(false);
                                }}>
                                {({ isSubmitting }) => (
                                    <Form>
                                        <Stack spacing='6'>
                                            <Stack spacing="5">
                                                <Field name='name'>
                                                    {({ field, form }: any) => (
                                                        <FormControl isInvalid={form.errors.name && form.touched.name}>
                                                            <FormLabel fontSize='sm' htmlFor='name'>Name</FormLabel>
                                                            <Input id='name' {...field} placeholder='Name' rounded='xl' bgColor='gray.50' />
                                                            <FormErrorMessage>{form.errors.name}</FormErrorMessage>
                                                        </FormControl>
                                                    )}
                                                </Field>
                                            </Stack>

                                            <Stack spacing='5'>
                                                <Field name='description'>
                                                    {({ field, form }: any) => (
                                                        <FormControl isInvalid={form.errors.description && form.touched.description}>
                                                            <FormLabel fontSize='sm' htmlFor='description'>Description</FormLabel>
                                                            <Textarea id='description' {...field} placeholder='Description' rounded='xl' bgColor='gray.50' />
                                                            <FormErrorMessage>{form.errors.description}</FormErrorMessage>
                                                        </FormControl>
                                                    )}
                                                </Field>
                                            </Stack>

                                            <Flex align='center' justify='end'>
                                                <Button fontSize='sm' mr='3' variant='ghost' onClick={createModalOnClose}>Cancel</Button>

                                                <Button fontSize='sm' rounded=' xl' colorScheme='blue' isLoading={isSubmitting} type='submit'>
                                                    Add
                                                </Button>
                                            </Flex>
                                        </Stack>
                                    </Form>
                                )}
                            </Formik>
                        </Box>
                    </ModalBody>
                </ModalContent >
            </Modal >

            {/* Edit Channel Modal */}
            <Modal isOpen={editModalIsOpen} onClose={closeEditModal} >
                <ModalOverlay />
                <ModalContent>
                    <ModalHeader>Edit Channel</ModalHeader>
                    <ModalCloseButton />
                    <ModalBody pb='5'>
                        <Text fontSize='sm' color='gray.500'>
                            Channels provide a space for organized discussions. With forums, you can discuss specific topics and not worry about talking over each other
                        </Text>

                        <Box mt='6'>
                            <Formik
                                initialValues={editable}
                                onSubmit={async (values, helpers) => {
                                    try {
                                        await form.handleEditSubmit(values as any);

                                        refreshData();

                                        helpers.resetForm();
                                        helpers.setSubmitting(false);

                                        closeEditModal();
                                    } catch (error: any) {
                                        error.message = JSON.parse(error.message);

                                        if (_.isArray(error.message)) {
                                            _.forEach(error.message, (message: string) => {
                                                toast(setToastContent('Channel edit failed', message));
                                            });
                                        } else {
                                            toast(setToastContent('Channel edit failed', error.message));
                                        }
                                    }

                                    helpers.setSubmitting(false);
                                }}>
                                {({ isSubmitting }) => (
                                    <Form>
                                        <Stack spacing='6'>
                                            <Stack spacing="5">
                                                <Field name='name'>
                                                    {({ field, form }: any) => (
                                                        <FormControl isInvalid={form.errors.name && form.touched.name}>
                                                            <FormLabel fontSize='sm' htmlFor='name'>Name</FormLabel>
                                                            <Input id='name' {...field} placeholder='Name' rounded='xl' bgColor='gray.50' />
                                                            <FormErrorMessage>{form.errors.name}</FormErrorMessage>
                                                        </FormControl>
                                                    )}
                                                </Field>
                                            </Stack>

                                            <Stack spacing='5'>
                                                <Field name='description'>
                                                    {({ field, form }: any) => (
                                                        <FormControl isInvalid={form.errors.description && form.touched.description}>
                                                            <FormLabel fontSize='sm' htmlFor='description'>Description</FormLabel>
                                                            <Textarea id='description' {...field} placeholder='Description' rounded='xl' bgColor='gray.50' />
                                                            <FormErrorMessage>{form.errors.description}</FormErrorMessage>
                                                        </FormControl>
                                                    )}
                                                </Field>
                                            </Stack>

                                            <Flex align='center'>
                                                <Button fontSize='sm' mr='3' rounded='xl' colorScheme='red' variant='ghost' onClick={deleteChannel}>Delete</Button>

                                                <Spacer />

                                                <Flex align='center' justify='end'>
                                                    <Button fontSize='sm' mr='3' rounded='xl' variant='ghost' onClick={closeEditModal}>Cancel</Button>

                                                    <Button fontSize='sm' rounded='xl' colorScheme='blue' isLoading={isSubmitting} type='submit'>
                                                        Update
                                                    </Button>
                                                </Flex>
                                            </Flex>
                                        </Stack>
                                    </Form>
                                )}
                            </Formik>
                        </Box >
                    </ModalBody >
                </ModalContent >
            </Modal >
        </>
    );
};

export const getServerSideProps: GetServerSideProps = async ({ req, res }) => {
    const token = getCookie('token', { req, res });
    const client = new Client(token as string);
    const channels = await client.resources('channels').all();

    return { props: { channels } };
};

Index.getLayout = function getLayout (page: NextPage) {
    return (<Layout>{page}</Layout>);
};

export default Index;
