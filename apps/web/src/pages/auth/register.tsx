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
    Text,
    FormHelperText
} from '@chakra-ui/react';
import { Field, Form, Formik } from 'formik';
import RegisterForm from '@/forms/Register';
import _ from 'lodash';
import useAuth from '@/hooks/useAuth';
import { NextPage } from 'next';
import Layout from '@/layouts/auth';
import type { NextPageWithLayout } from '../_app';
import Logo from '@/components/Logo';
import { useRouter } from 'next/router';
import Head from 'next/head';

const Register: NextPageWithLayout = () => {
    const router = useRouter();
    const { mutate } = useAuth({
        middleware: 'guest',
        redirectIfAuthenticated: '/users/profile'
    });

    const toast = useToast();
    const register = new RegisterForm();

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
                <title>Register | Ignite</title>
            </Head>

            <Container maxW='container.xl' py={{ base: '12', md: '24' }} px={{ base: '0', sm: '8' }}>
                <Stack spacing='8' maxW='md'>
                    <Stack spacing='6'>
                        <Box
                            py={{ base: '0', sm: '8' }}
                            px={{ base: '4', sm: '10' }}
                            bg={{ base: 'transparent', sm: 'white' }}
                            boxShadow={{ base: 'none', sm: 'md' }}
                            borderRadius={{ base: 'none', sm: 'xl' }}
                        >
                            <Stack spacing='6'>
                                <HStack justify='center'>
                                    <Logo href='/' />
                                </HStack>

                                <Stack spacing={{ base: '2', md: '3' }} textAlign='center'>
                                    <Heading size={{ base: 'sm', md: 'lg' }}>Create an account</Heading>

                                    <HStack spacing='1' justify='center'>
                                        <Text color='gray.600'>Already have an account?</Text>
                                        <Button variant='link' colorScheme='blue' onClick={() => router.push('/auth/login')}>
                                            Sign in
                                        </Button>
                                    </HStack>
                                </Stack>

                                <Stack spacing='6'>
                                    <Formik
                                        initialValues={register.getInitialValues()}
                                        onSubmit={async (values, helpers) => {
                                            await register.onSubmit(values, helpers, {
                                                onSuccess: () => mutate(),
                                                onFail: (error) => {
                                                    if (!error.response) {
                                                        toast(errorToast('Register failed.', error.message));
                                                    }

                                                    const { message } = error.response.data;

                                                    if (_.isArray(message)) {
                                                        _.forEach(message, (msg) => {
                                                            toast(errorToast('Invalid inputs.', msg));
                                                        });
                                                    } else {
                                                        toast(errorToast('Register failed.', message));
                                                    }
                                                }
                                            });
                                        }}>
                                        {({ isSubmitting }) => (
                                            <Form>
                                                <Stack spacing='6'>
                                                    <Stack spacing="5">
                                                        <Field name='name'>
                                                            {({ field, form }: any) => (
                                                                <FormControl isInvalid={form.errors.name && form.touched.name} isRequired>
                                                                    <FormLabel fontSize='sm' htmlFor='name'>Name</FormLabel>
                                                                    <Input id='name' {...field} placeholder='Name' rounded='xl' bgColor='gray.50' />
                                                                    <FormErrorMessage>{form.errors.name}</FormErrorMessage>
                                                                </FormControl>
                                                            )}
                                                        </Field>
                                                    </Stack>

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
                                                        <Field name='password'>
                                                            {({ field, form }: any) => (
                                                                <FormControl isInvalid={form.errors.password && form.touched.password} isRequired>
                                                                    <FormLabel fontSize='sm' htmlFor='password'>Password</FormLabel>
                                                                    <Input {...field} id='password' type='password' placeholder='Password' rounded='xl' bgColor='gray.50' autoComplete='current-password' />
                                                                    <FormHelperText>At least 8 characters long.</FormHelperText>
                                                                    <FormErrorMessage>{form.errors.password}</FormErrorMessage>
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
                                                            Create Account
                                                        </Button>
                                                    </Stack>
                                                </Stack>
                                            </Form>
                                        )}
                                    </Formik>
                                </Stack>
                            </Stack>
                        </Box>
                    </Stack>
                </Stack>
            </Container>
        </>
    );
};

Register.getLayout = function getLayout (page: NextPage) {
    return (
        <Layout>{page}</Layout>
    );
};

export default Register;
