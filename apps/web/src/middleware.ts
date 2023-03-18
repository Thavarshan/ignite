import type { NextRequest } from 'next/server';
import MiddlewareConditions from '@/lib/Contracts/Http/MiddlewareConditions';
import Handler from '@/lib/Http/Middleware/Handler';

export async function middleware (request: NextRequest) {
    const handler = new Handler(request, conditions);

    return await handler.handle();
}

const conditions: MiddlewareConditions[] = [
    {
        path: '/auth(/:path)',
        guard: 'guest'
    },
    {
        path: '/tickets(/:path)',
        guard: 'auth'
    },
    {
        path: '/users(/:path)',
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
