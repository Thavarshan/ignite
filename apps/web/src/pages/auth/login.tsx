import React from 'react';
import { authOptions } from '@/pages/api/auth/[...nextauth]';
import { getServerSession } from 'next-auth/next';
import {
    Flex,
    Box,
    Heading,
    FormControl,
    FormLabel,
    Input,
    Button,
    FormErrorMessage
} from '@chakra-ui/react';
import { Field, Form, Formik } from 'formik';
import LoginForm from '@/forms/Login';

const Login = () => {
    const login = new LoginForm();

    return (
        <>
            <Flex width='Full' align='center' justifyContent='center' p={10} bgColor='gray.100'>
                <Box px={6} py={5} maxW='xl' w='lg' borderWidth={1} borderRadius='2xl' bgColor='white' boxShadow='lg'>
                    <Box textAlign='center'>
                        <Heading size='md'>Sign in to your account</Heading>
                    </Box>

                    <Box mt={8} textAlign='left'>
                        <Formik
                            initialValues={login.getInitialValues()}
                            onSubmit={login.onSubmit()}>
                            {({ isSubmitting }) => (
                                <Form>
                                    <Box>
                                        <Field name='email'>
                                            {({ field, form }: any) => (
                                                <FormControl isInvalid={form.errors.email && form.touched.email}>
                                                    <FormLabel>Email</FormLabel>
                                                    <Input {...field} placeholder='Email' rounded='xl' bgColor='gray.50' />
                                                    <FormErrorMessage>{form.errors.email}</FormErrorMessage>
                                                </FormControl>
                                            )}
                                        </Field>
                                    </Box>

                                    <Box mt='4'>
                                        <Field name='password'>
                                            {({ field, form }: any) => (
                                                <FormControl isInvalid={form.errors.password && form.touched.password}>
                                                    <FormLabel>Password</FormLabel>
                                                    <Input {...field} placeholder='Password' rounded='xl' bgColor='gray.50' />
                                                    <FormErrorMessage>{form.errors.password}</FormErrorMessage>
                                                </FormControl>
                                            )}
                                        </Field>
                                    </Box>

                                    <Box mt='8'>
                                        <Button
                                            w='full'
                                            rounded='xl'
                                            colorScheme='blue'
                                            isLoading={isSubmitting}
                                            type='submit'>
                                            Sign In
                                        </Button>
                                    </Box>
                                </Form>
                            )}
                        </Formik>
                    </Box>
                </Box>
            </Flex>
        </>
    );
};

export async function getServerSideProps (context: any) {
    const session: any = await getServerSession(context.req, context.res, authOptions);

    if (session?.accessToken) {
        return {
            redirect: {
                destination: context.req.headers.referer || '/',
                permanent: false
            }
        };
    }

    return { props: { session } };
}

export default Login;
