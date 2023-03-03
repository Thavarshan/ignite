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
    useToast,
    InputGroup,
    Container,
    Stack,
    HStack,
    Text
} from '@chakra-ui/react';
import { Field, Form, Formik } from 'formik';
import ResetPasswordForm from '@/forms/ResetPassword';
import _ from 'lodash';
import useAuth from '@/hooks/useAuth';
import { NextPage } from 'next';
import Layout from '@/layouts/auth';
import type { NextPageWithLayout } from '../_app';
import Logo from '@/components/Logo';
import Head from 'next/head';
import { toast as notify, redirect } from '@/utils/helpers';

const ResetPassword: NextPageWithLayout = ({ email, token }) => {
    useAuth({
        middleware: 'guest',
        redirectIfAuthenticated: '/users/profile'
    });

    const toast = useToast();
    const resetPassword = new ResetPasswordForm();

    return (
        <>
            <Head>
                <title>Reset Password | Ignite</title>
            </Head>

            <Container maxW='lg' py={{ base: '12', md: '24' }} px={{ base: '0', sm: '8' }}>
                <Stack spacing='8'>
                    <Stack spacing='6'>
                        <HStack justify='center'>
                            <Logo href='/' color='blue.300' />
                        </HStack>

                        <Stack spacing={{ base: '2', md: '3' }} textAlign='center'>
                            <Heading size={{ base: 'sm', md: 'lg' }} color='white'>Reset your password</Heading>

                            <HStack spacing='1' justify='center'>
                                <Text color='blue.300'>
                                    Use a password that has at least 16 characters, use at least one number, one uppercase letter, one lowercase letter and one special symbol.
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
                                    enableReinitialize={true}
                                    initialValues={_.merge(resetPassword.getInitialValues(), { email, token })}
                                    onSubmit={async (values, helpers) => {
                                        await resetPassword.onSubmit(values, helpers, {
                                            onSuccess: () => redirect('/auth/login'),

                                            onFail: (error) => {
                                                if (!error.response) {
                                                    toast(notify('Password reset failed.', error.message, 'error'));
                                                }

                                                const { message } = error.response.data;

                                                if (_.isArray(message)) {
                                                    _.forEach(message, (msg) => {
                                                        toast(notify('Invalid input.', msg, 'error'));
                                                    });
                                                } else {
                                                    toast(notify('Password reset failed.', message, 'error'));
                                                }
                                            }
                                        });
                                    }}>
                                    {({ isSubmitting }) => (
                                        <Form>
                                            <Stack spacing='6'>
                                                <Stack spacing="5">
                                                    <Field name='email'>
                                                        {({ field, form }: any) => (
                                                            <FormControl isInvalid={form.errors.email && form.touched.email}>
                                                                <FormLabel fontSize='sm' htmlFor='email'>Email</FormLabel>
                                                                <Input id='email' {...field} placeholder='Email' rounded='xl' bgColor='gray.50' />
                                                                <FormErrorMessage>{form.errors.email}</FormErrorMessage>
                                                            </FormControl>
                                                        )}
                                                    </Field>
                                                </Stack>

                                                <Stack spacing='5'>
                                                    <Field name='password'>
                                                        {({ field, form }: any) => (
                                                            <FormControl isInvalid={form.errors.password && form.touched.password}>
                                                                <FormLabel fontSize='sm' htmlFor='password'>Password</FormLabel>
                                                                <InputGroup>
                                                                    <Input {...field} id='password' type='password' placeholder='Password' rounded='xl' bgColor='gray.50' autoComplete='current-password' />
                                                                </InputGroup>
                                                                <FormErrorMessage>{form.errors.password}</FormErrorMessage>
                                                            </FormControl>
                                                        )}
                                                    </Field>
                                                </Stack>

                                                <Stack spacing='5'>
                                                    <Field name='passwordConfirmation'>
                                                        {({ field, form }: any) => (
                                                            <FormControl isInvalid={form.errors.passwordConfirmation && form.touched.passwordConfirmation}>
                                                                <FormLabel fontSize='sm' htmlFor='passwordConfirmation'>Password confirmation</FormLabel>
                                                                <InputGroup>
                                                                    <Input {...field} id='passwordConfirmation' type='password' placeholder='Password confirmation' rounded='xl' bgColor='gray.50' autoComplete='current-password' />
                                                                </InputGroup>
                                                                <FormErrorMessage>{form.errors.passwordConfirmation}</FormErrorMessage>
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
                                                        Reset password
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
            </Container >
        </>
    );
};

ResetPassword.getLayout = function getLayout (page: NextPage) {
    return (
        <Layout>{page}</Layout>
    );
};

export async function getServerSideProps ({ query }: any) {
    const { email, token } = query;

    if (!token) {
        return {
            redirect: {
                destination: '/auth/login',
                permanent: false
            }
        };
    }

    return { props: { email, token } };
}

export default ResetPassword;
