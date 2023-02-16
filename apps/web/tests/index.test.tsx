import { render, screen } from '@testing-library/react';
import Web from '@/pages/index';

describe('Home', () => {
    it('renders a heading', () => {
        render(<Web />);

        const heading = screen.getByRole('heading', {
            name: 'Web',
        });

        expect(heading).toBeInTheDocument();
    });
});
