import { Box } from '@chakra-ui/react';
import React from 'react';

const Layout = ({ children }: any) => {
    return (
        <Box w='100%' h='100vh' bgGradient='linear(to-tl, #3b82f6, #3730a3)'>
            {children}
        </Box>
    );
};

export default Layout;
