import type { NextRequest } from 'next/server';
import MiddlewareConditions from '@/contracts/lib/MiddlewareConditions';
import Handler from '@/lib/middleware/handler';

export function middleware (request: NextRequest) {
    const handler = new Handler(request);

    handler.setConditions(conditions);

    return handler.handle();
}

const conditions: MiddlewareConditions[] = [
    {
        path: '/auth/register',
        guard: 'guest'
    },
    {
        path: '/auth/forgot-password',
        guard: 'guest'
    },
    {
        path: '/auth/login',
        guard: 'guest'
    },
    {
        path: '/tickets',
        guard: 'auth'
    },
    {
        path: '/tickets(/:id)',
        guard: 'auth'
    },
    {
        path: '/test',
        guard: '*',
        redirectTo: '/'
    }
];

export const config = {
    unstable_allowDynamic: [
        '**/node_modules/lodash/_root.js'
    ]
};
