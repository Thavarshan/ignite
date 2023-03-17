/**
 * @jest-environment node
 */

import { middleware } from '@/middleware';
import { NextURL } from 'next/dist/server/web/next-url';
import { NextRequest } from 'next/server';
import { instance, mock, reset, when } from 'ts-mockito';

const mockedRequest: NextRequest = mock(NextRequest);

describe('Middleware', () => {
    afterEach(() => {
        reset(mockedRequest);
    });

    it('should handle route guards', async () => {
        const domain = 'https://example.com';
        const url = new URL('/test', domain);
        const nextUrl = new NextURL(url);
        const cookie = {
            get: () => ({
                name: 'token',
                value: 'fake-token'
            } as any)
        } as any;

        when(mockedRequest.nextUrl).thenReturn(nextUrl);
        when(mockedRequest.url).thenReturn(domain);
        when(mockedRequest.cookies).thenReturn(cookie);

        const response = await middleware(instance(mockedRequest));

        expect(response).toBeDefined();
        expect(response?.status).toBe(307);

        expect(response?.headers.get('location')).toBe(domain + '/');
    });
});
