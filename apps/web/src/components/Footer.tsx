import {
    Box,
    Button,
    ButtonGroup,
    Container,
    Divider,
    IconButton,
    Input,
    Stack,
    Text
} from '@chakra-ui/react';
import { useRouter } from 'next/router';
import { FaGithub, FaLinkedin, FaTwitter } from 'react-icons/fa';
import Logo from './Logo';

const Footer = () => {
    const router = useRouter();

    return (
        <>
            <Box as='footer' bg='white' role='contentinfo'>
                <Container maxW='container.xl'>
                    <Stack spacing='8' direction={{ base: 'column', md: 'row' }} justify='space-between' py={{ base: '12', md: '16' }}>
                        <Stack spacing={{ base: '6', md: '8' }} align='start'>
                            <Logo />

                            <Text color='gray.500' maxW='sm'>
                                A powerfully simple discussion forum you can easily customize to make as unique as your community. It is essentially a forum platform for building communities.
                            </Text>
                        </Stack>

                        <Stack direction={{ base: 'column-reverse', md: 'column', lg: 'row' }} spacing={{ base: '12', md: '8' }}>
                            <Stack direction='row' spacing='8'>
                                <Stack spacing='4' minW='36' flex='1'>
                                    <Text fontSize='sm' fontWeight='semibold' color='subtle'>
                                        Product
                                    </Text>

                                    <Stack spacing='3' shouldWrapChildren>
                                        <Button variant='link' size='sm'>How it works</Button>
                                        <Button variant='link' size='sm'>Pricing</Button>
                                        <Button variant='link' size='sm'>Use Cases</Button>
                                    </Stack>
                                </Stack>

                                <Stack spacing='4' minW='36' flex='1'>
                                    <Text fontSize='sm' fontWeight='semibold' color='subtle'>
                                        Legal
                                    </Text>

                                    <Stack spacing='3' shouldWrapChildren>
                                        <Button variant='link' size='sm'>Privacy</Button>
                                        <Button variant='link' size='sm'>Terms</Button>
                                        <Button variant='link' size='sm' onClick={() => router.push('/legal/license')}>License</Button>
                                    </Stack>
                                </Stack>
                            </Stack>

                            <Stack spacing='4'>
                                <Text fontSize='sm' fontWeight='semibold' color='subtle'>
                                    Stay up to date
                                </Text>

                                <Stack spacing='2' direction={{ base: 'column', sm: 'row' }} maxW={{ lg: '360px' }}>
                                    <Input placeholder='Enter your email' type='email' rounded='xl' required />

                                    <Button type='submit' flexShrink={0} rounded='xl'>
                                        Subscribe
                                    </Button>
                                </Stack>
                            </Stack>
                        </Stack>
                    </Stack>
                    <Divider />
                    <Stack pt='8' pb='12' justify='space-between' direction={{ base: 'column-reverse', md: 'row' }} align='center'>
                        <Text fontSize='xs' color='gray.400'>
                            &copy; {new Date().getFullYear()} Ignite. All rights reserved.
                        </Text>

                        <ButtonGroup variant='ghost'>
                            <IconButton
                                as='a'
                                href='#'
                                aria-label='LinkedIn'
                                icon={<FaLinkedin fontSize='1.25rem' />}
                            />
                            <IconButton as='a' href='#' aria-label='GitHub' icon={<FaGithub fontSize='1.25rem' />} />
                            <IconButton as='a' href='#' aria-label='Twitter' icon={<FaTwitter fontSize='1.25rem' />} />
                        </ButtonGroup>
                    </Stack>
                </Container>
            </Box>
        </>
    );
};

export default Footer;
