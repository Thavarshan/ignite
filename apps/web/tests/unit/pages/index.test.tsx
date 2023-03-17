import { render, screen } from '@testing-library/react';
import Home from '@/pages/index';
import mockRouter from 'next-router-mock';
import { ChakraProvider } from '@chakra-ui/react';

jest.mock('next/router', () => require('next-router-mock'));

describe('Home', () => {
    it('renders a heading', async () => {
        mockRouter.push('/auth/login');

        render(
            <ChakraProvider>
                <Home />
            </ChakraProvider>
        );

        const heading = screen.getByText('Ready to Grow?');

        expect(heading).toBeInTheDocument();
    });
});
