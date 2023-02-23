// export { default } from 'next-auth/middleware';
import { NextRequestWithAuth, withAuth } from 'next-auth/middleware';
// import { NextResponse } from 'next/server';

export default withAuth((request: NextRequestWithAuth) => {
    console.log('token: ', request.nextauth.token);
}, {
    callbacks: { authorized: ({ token }) => !!token },
    pages: {
        signIn: '/auth/login',
        signOut: '/',
        error: '/api/auth/error',
    }
});

export const config = {
    matcher: ['/users/:path*'],
};
