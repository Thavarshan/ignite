import {
    Box,
    Button,
    Container,
    Heading,
    Stack,
    Text
} from '@chakra-ui/react';
import Head from 'next/head';

const Home = () => {
    return (
        <>
            <Head>
                <title>Ignite | Forum Platform Builder</title>
            </Head>

            <Box as='section' bg='white'>
                <Container py={{ base: '16', md: '24' }}>
                    <Stack spacing={{ base: '8', md: '10' }}>
                        <Stack spacing={{ base: '4', md: '5' }} align='center'>
                            <Heading size={{ base: 'sm', md: 'md', xl: 'lg' }} color='gray.900'>About</Heading>

                            <Text color='gray.500' maxW='2xl' textAlign='center' fontSize='xl'>
                                With this beautiful and responsive React components you will realize your next project
                                in no time.
                            </Text>
                        </Stack>

                        <Stack spacing='3' direction={{ base: 'column', sm: 'row' }} justify='center'>
                            <Button variant='solid' colorScheme='blue' rounded='xl' size='lg'>
                                Get started
                            </Button>

                            <Button variant='ghost' rounded='xl' size='lg'>
                                Learn more
                            </Button>
                        </Stack>
                    </Stack>
                </Container>
            </Box>
        </>
    );
};

export default Home;
