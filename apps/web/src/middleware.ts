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
