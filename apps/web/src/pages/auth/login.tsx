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
import { signIn } from 'next-auth/react';

const Login = () => {
    return (
        <>
            <Flex width='Full' align='center' justifyContent='center' p={10} bgColor='gray.100'>
                <Box px={6} py={5} maxW='xl' w='lg' borderWidth={1} borderRadius='2xl' bgColor='white' boxShadow='lg'>
                    <Box textAlign='center'>
                        <Heading size='md'>Sign in to your account</Heading>
                    </Box>

                    <Box mt={8} textAlign='left'>
                        <Formik
                            initialValues={{ email: '', password: '' }}
                            onSubmit={async (values, actions) => {
                                await signIn('credentials', {
                                    email: values.email,
                                    password: values.password,
                                    redirect: true,
                                    callbackUrl: '/users/James'
                                });

                                actions.setSubmitting(false);
                                actions.resetForm();
                            }}
                        >
                            {(props) => (
                                <Form>
                                    <Box>
                                        <Field name='email'>
                                            {({ field, form }: any) => (
                                                <FormControl isInvalid={form.errors.email && form.touched.email}>
                                                    <FormLabel>Email</FormLabel>
                                                    <Input {...field} placeholder='Email' rounded='xl' />
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
                                                    <Input {...field} placeholder='Password' rounded='xl' />
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
                                            isLoading={props.isSubmitting}
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
