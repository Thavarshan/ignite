/* eslint-disable no-console */
import React, { useState } from 'react';
import {
    Box,
    Heading,
    FormControl,
    FormLabel,
    Input,
    Button,
    FormErrorMessage,
    InputGroup,
    InputRightElement,
    IconButton,
    Container,
    Stack,
    HStack,
    Text,
    Checkbox
} from '@chakra-ui/react';
import { Field, Form, Formik } from 'formik';
import { HiEye, HiEyeOff } from 'react-icons/hi';
import { NextPage } from 'next';
import Layout from '@/layouts/auth';
import type { NextPageWithLayout } from '../_app';
import Logo from '@/components/Logo';
import Head from 'next/head';
import { useRouter } from 'next/router';
import useAuth from '@/hooks/useAuth';

const Login: NextPageWithLayout = () => {
    const router = useRouter();
    const [showPassword, setShowPassword] = useState(false);
    const { login } = useAuth();

    return (
        <>
            <Head>
                <title>Login | Ignite</title>
            </Head>

            <Container maxW='lg' py={{ base: '12', md: '24' }} px={{ base: '0', sm: '8' }}>
                <Stack spacing='8'>
                    <Stack spacing='6'>
                        <HStack justify='center'>
                            <Logo href='/' color='blue.300' />
                        </HStack>

                        <Stack spacing={{ base: '2', md: '3' }} textAlign='center'>
                            <Heading size={{ base: 'sm', md: 'lg' }} color='white'>Log in to your account</Heading>

                            <HStack spacing='1' justify='center'>
                                <Text color='blue.300'>Don&apos;t have an account?</Text>

                                <Button variant='link' color='white' colorScheme='whiteAlpha' onClick={() => router.push('/auth/register')}>
                                    Sign up
                                </Button>
                            </HStack>
                        </Stack>

                        <Box py={{ base: '0', sm: '8' }} px={{ base: '4', sm: '10' }} bg={{ base: 'transparent', sm: 'white' }} boxShadow={{ base: 'none', sm: 'md' }} borderRadius={{ base: 'none', sm: 'xl' }}>
                            <Stack spacing='6'>
                                <Formik
                                    initialValues={{ email: '', password: '' }}
                                    onSubmit={async (values, helpers) => {
                                        try {
                                            await login(values);

                                            router.push('/tickets');
                                        } catch (error: any) {
                                            //
                                        }

                                        helpers.setSubmitting(false);
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
                                                                    <Input {...field} id='password' type={showPassword ? 'text' : 'password'} placeholder='Password' rounded='xl' bgColor='gray.50' autoComplete='current-password' />
                                                                    <InputRightElement width='4.5rem'>
                                                                        <IconButton
                                                                            variant='link'
                                                                            aria-label={showPassword ? 'Mask password' : 'Reveal password'}
                                                                            icon={showPassword ? <HiEyeOff /> : <HiEye />}
                                                                            onClick={() => setShowPassword(!showPassword)} />
                                                                    </InputRightElement>
                                                                </InputGroup>
                                                                <FormErrorMessage>{form.errors.password}</FormErrorMessage>
                                                            </FormControl>
                                                        )}
                                                    </Field>
                                                </Stack>

                                                <HStack justify='space-between'>
                                                    <Checkbox defaultChecked>
                                                        <Text fontSize='sm'>Remember me</Text>
                                                    </Checkbox>

                                                    <Button variant='link' colorScheme='blue' size='sm' onClick={() => router.push('/auth/forgot-password')}>
                                                        Forgot password?
                                                    </Button>
                                                </HStack>

                                                <Stack spacing='5'>
                                                    <Button
                                                        w='full'
                                                        rounded='xl'
                                                        colorScheme='blue'
                                                        isLoading={isSubmitting}
                                                        type='submit'>
                                                        Sign in
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

Login.getLayout = function getLayout (page: NextPage) {
    return (<Layout>{page}</Layout>);
};

export default Login;
