import type { User } from '@/types/user';

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    jetstream: unknown;

    features: { [key: string]: boolean };

    flash: {
        banner: string | undefined;
        bannerStyle: 'success' | 'danger' | 'warning' | 'info' | 'none';
        message: string | undefined;
        token: string | undefined;
    };

    auth: {
        user: User;
    };
};
