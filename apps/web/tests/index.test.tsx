import { render, screen } from '@testing-library/react';
import Home from '@/pages/index';
import { useSession } from 'next-auth/react';
import mockRouter from 'next-router-mock';

jest.mock('next-auth/react');
jest.mock('next/router', () => require('next-router-mock'));

describe('Home', () => {
    it('renders a heading', async () => {
        const mockUseSession = useSession as jest.Mock;
        mockUseSession.mockReturnValue({
            status: 'unauthenticated',
            data: null,
        });

        mockRouter.push('/auth/login');

        render(<Home />);

        const heading = screen.getByRole('heading', {
            name: 'Hello',
        });

        expect(heading).toBeInTheDocument();
    });
});
