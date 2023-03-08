/* eslint-disable no-console */
import React from 'react';
import {
    Box,
    Heading,
    FormControl,
    FormLabel,
    Input,
    Button,
    FormErrorMessage,
    Container,
    Stack,
    HStack,
    Text
} from '@chakra-ui/react';
import { Field, Form, Formik } from 'formik';
import { NextPage } from 'next';
import Layout from '@/layouts/auth';
import type { NextPageWithLayout } from '../_app';
import Logo from '@/components/Logo';
import Head from 'next/head';

const ForgotPassword: NextPageWithLayout = () => {
    return (
        <>
            <Head>
                <title>Forgot Password | Ignite</title>
            </Head>

            <Container maxW='lg' py={{ base: '12', md: '24' }} px={{ base: '0', sm: '8' }}>
                <Stack spacing='8'>
                    <Stack spacing='6'>
                        <HStack justify='center'>
                            <Logo href='/' color='blue.300' />
                        </HStack>

                        <Stack spacing={{ base: '2', md: '3' }} textAlign='center'>
                            <Heading size={{ base: 'sm', md: 'lg' }} color='white'>Forgot your password?</Heading>

                            <HStack spacing='1' justify='center'>
                                <Text color='blue.300'>
                                    No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                                </Text>
                            </HStack>
                        </Stack>

                        <Box
                            py={{ base: '0', sm: '8' }}
                            px={{ base: '4', sm: '10' }}
                            bg={{ base: 'transparent', sm: 'white' }}
                            boxShadow={{ base: 'none', sm: 'md' }}
                            borderRadius={{ base: 'none', sm: 'xl' }}
                        >
                            <Stack spacing='6'>
                                <Formik
                                    initialValues={{ email: '' }}
                                    onSubmit={async (_values, _helpers) => {
                                        //
                                    }}>
                                    {({ isSubmitting }) => (
                                        <Form>
                                            <Stack spacing='6'>
                                                <Stack spacing="5">
                                                    <Field name='email'>
                                                        {({ field, form }: any) => (
                                                            <FormControl isInvalid={form.errors.email && form.touched.email} isRequired>
                                                                <FormLabel fontSize='sm' htmlFor='email'>Email</FormLabel>
                                                                <Input id='email' {...field} placeholder='Email' rounded='xl' bgColor='gray.50' />
                                                                <FormErrorMessage>{form.errors.email}</FormErrorMessage>
                                                            </FormControl>
                                                        )}
                                                    </Field>
                                                </Stack>

                                                <Stack spacing='5'>
                                                    <Button
                                                        w='full'
                                                        rounded='xl'
                                                        colorScheme='blue'
                                                        isLoading={isSubmitting}
                                                        type='submit'>
                                                        Email password reset link
                                                    </Button>
                                                </Stack>
                                            </Stack>
                                        </Form>
                                    )}
                                </Formik>
                            </Stack>
                        </Box>
                    </Stack>
                </Stack>
            </Container>
        </>
    );
};

ForgotPassword.getLayout = function getLayout (page: NextPage) {
    return (
        <Layout>{page}</Layout>
    );
};

export default ForgotPassword;
