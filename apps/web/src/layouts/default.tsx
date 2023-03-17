import {
    Flex,
    Box,
    Text
} from '@chakra-ui/react';
import React from 'react';
import Header from '@/components/Header';
import { url } from '@/lib/Utils/url';
import Footer from '@/components/Footer';
import Banner from '@/components/Banner';

const Layout = ({ children }: any) => {
    const links = {
        Home: url(),
        About: url('/about'),
        Contact: url('/contact')
    };

    return (
        <>
            <Flex direction='column' flex='1'>
                <Banner>
                    <Box>
                        <Text fontWeight='medium' fontSize='sm' color='gray.900'>We just launched our new product. Read our press release</Text>
                    </Box>
                </Banner>

                <Header links={links} />

                <Box bg='gray.50' w='100%'>
                    {children}
                </Box>

                <Footer />
            </Flex>
        </>
    );
};

export default Layout;
