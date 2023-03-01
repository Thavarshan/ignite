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
    UseToastOptions,
    Container,
    Stack,
    HStack,
    Text
} from '@chakra-ui/react';
import { Field, Form, Formik } from 'formik';
import ForgotPasswordForm from '@/forms/ForgotPassword';
import _ from 'lodash';
import useAuth from '@/hooks/useAuth';
import { NextPage } from 'next';
import Layout from '@/layouts/auth';
import type { NextPageWithLayout } from '../_app';
import Logo from '@/components/Logo';
// import { useRouter } from 'next/router';
import Head from 'next/head';

const ForgotPassword: NextPageWithLayout = () => {
    // const router = useRouter();
    const { mutate } = useAuth({
        middleware: 'guest',
        redirectIfAuthenticated: '/users/profile'
    });

    const toast = useToast();
    const forgotPassword = new ForgotPasswordForm();

    const errorToast = (title: string, description: string): UseToastOptions => {
        return {
            title: title,
            description: description,
            status: 'error',
            duration: 9000,
            isClosable: true,
            variant: 'subtle',
            position: 'bottom-right'
        };
    };

    return (
        <>
            <Head>
                <title>Forgot Password | Ignite</title>
            </Head>

            <Container maxW='lg' py={{ base: '12', md: '24' }} px={{ base: '0', sm: '8' }}>
                <Stack spacing='8'>
                    <Stack spacing='6'>
                        <HStack justify='center'>
                            <Logo href='/' />
                        </HStack>

                        <Stack spacing={{ base: '2', md: '3' }} textAlign='center'>
                            <Heading size={{ base: 'sm', md: 'lg' }}>Forgot your password?</Heading>

                            <HStack spacing='1' justify='center'>
                                <Text color='gray.600'>
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
                                    initialValues={forgotPassword.getInitialValues()}
                                    onSubmit={async (values, helpers) => {
                                        await forgotPassword.onSubmit(values, helpers, {
                                            onSuccess: () => mutate(),
                                            onFail: (error) => {
                                                if (!error.response) {
                                                    toast(errorToast('Request failed.', error.message));
                                                }

                                                const { message } = error.response.data;

                                                if (_.isArray(message)) {
                                                    _.forEach(message, (msg) => {
                                                        toast(errorToast('Invalid inputs.', msg));
                                                    });
                                                } else {
                                                    toast(errorToast('Request failed.', message));
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
