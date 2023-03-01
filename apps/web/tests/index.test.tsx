import { render, screen } from '@testing-library/react';
import Home from '@/pages/index';
import mockRouter from 'next-router-mock';

jest.mock('next/router', () => require('next-router-mock'));

describe('Home', () => {
    it('renders a heading', async () => {
        mockRouter.push('/auth/login');

        render(<Home />);

        const heading = screen.getByRole('heading', {
            name: 'Hello'
        });

        expect(heading).toBeInTheDocument();
    });
});
