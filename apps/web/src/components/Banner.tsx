import {
    Box,
    Button,
    CloseButton,
    Container,
    Icon,
    Square,
    Stack,
    useBreakpointValue
} from '@chakra-ui/react';
import { FiInfo } from 'react-icons/fi';

const Banner = ({ children }: any) => {
    const isMobile = useBreakpointValue({ base: true, md: false });

    return (
        <Box bg='gray.50' boxShadow='dark-lg'>
            <Container py={{ base: '4', md: '2.5' }} maxW='container.xl' position='relative'>
                <CloseButton display={{ sm: 'none' }} position='absolute' right='2' top='2' />

                <Stack
                    direction={{ base: 'column', sm: 'row' }}
                    justify='space-between'
                    spacing={{ base: '3', md: '2' }}
                >
                    <Stack
                        spacing='2'
                        direction={{ base: 'column', md: 'row' }}
                        align={{ base: 'start', md: 'center' }}
                    >
                        {!isMobile && (
                            <Square size='10' bg='bg-subtle' borderRadius='md'>
                                <Icon as={FiInfo} boxSize='6' color='blue.500' />
                            </Square>
                        )}
                        <Stack
                            direction={{ base: 'column', md: 'row' }}
                            spacing={{ base: '0.5', md: '1.5' }}
                            pe={{ base: '4', sm: '0' }}
                        >
                            {children}
                        </Stack>
                    </Stack>
                    <Stack
                        direction={{ base: 'column', sm: 'row' }}
                        spacing={{ base: '3', sm: '2' }}
                        align={{ base: 'stretch', sm: 'center' }}
                    >
                        <Button variant='solid' colorScheme='blue' size='sm' width='full'>
                            Read more
                        </Button>

                        <CloseButton display={{ base: 'none', sm: 'inline-flex' }} />
                    </Stack>
                </Stack>
            </Container>
        </Box>
    );
};

export default Banner;
