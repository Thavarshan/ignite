import Navbar from '@/contracts/components/Navbar';
import {
    Box,
    Button,
    ButtonGroup,
    Container,
    Flex,
    HStack,
    IconButton,
    useBreakpointValue
} from '@chakra-ui/react';
import { AiOutlineArrowRight } from 'react-icons/ai';
import _ from 'lodash';
import { useRouter } from 'next/router';
import { FiMenu } from 'react-icons/fi';
import Logo from './Logo';

const Navbar = ({ links }: Navbar) => {
    const router = useRouter();
    const isDesktop = useBreakpointValue({ base: false, lg: true });

    return (
        <Box as='nav' bg='white' boxShadow='sm' role='navigation'>
            <Container py={{ base: '3', lg: '4' }} maxW='container.xl'>
                <HStack spacing='10' justify='space-between'>
                    <Logo height={9} width={9} />

                    {isDesktop ? (
                        <Flex justify='space-between' flex='1'>
                            <ButtonGroup variant='ghost' spacing='3'>
                                {_.map(links, (link: URL, page: string) => (
                                    <Button key={page} fontSize='sm' rounded='xl' onClick={() => router.push(link)}>{page}</Button>
                                ))}
                            </ButtonGroup>

                            <HStack spacing='3'>
                                <Button variant='ghost' fontSize='sm' rounded='xl' onClick={() => router.push('/auth/login')}>Sign in</Button>
                                <Button colorScheme='blue' variant='outline' fontSize='sm' rounded='xl' rightIcon={<AiOutlineArrowRight />} onClick={() => router.push('/auth/register')}>Sign up</Button>
                            </HStack>
                        </Flex>
                    ) : (
                        <IconButton
                            variant='ghost'
                            icon={<FiMenu fontSize='1.25rem' />}
                            aria-label='Open Menu'
                        />
                    )}
                </HStack>
            </Container>
        </Box>
    );
};

export default Navbar;
