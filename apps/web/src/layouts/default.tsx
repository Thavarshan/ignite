import { Box } from '@chakra-ui/react';
import React from 'react';

const Layout = ({ children }: any) => {
    return (
        <Box bg='gray.50' h='100vh' w='100%'>
            {children}
        </Box>
    );
};

export default Layout;
