import Credentials from '@/contracts/auth/Credentials';
import Session from '@/contracts/auth/Session';
import NextAuth, { NextAuthOptions } from 'next-auth';
import { JWT } from 'next-auth/jwt';
import CredentialsProvider from 'next-auth/providers/credentials';

export const authOptions: NextAuthOptions = {
    providers: [
        CredentialsProvider({
            // The name to display on the sign in form (e.g. 'Sign in with...')
            name: 'Credentials',
            // The credentials is used to generate a suitable form on the sign in page.
            // You can specify whatever fields you are expecting to be submitted.
            // e.g. domain, username, password, 2FA token, etc.
            // You can pass any HTML attribute to the <input> tag through the object.
            credentials: {},

            async authorize (credentials) {
                const { email, password }: Credentials = credentials as any;

                const response = await fetch('http://localhost:3001/login', {
                    method: 'POST',
                    body: JSON.stringify({ email, password }),
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const token = await response.json();

                return (response.ok && token) ? token : null;
            }
        })
    ],

    callbacks: {
        async jwt ({ token, user }): Promise<JWT> {
            return { ...token, ...user };
        },

        async session ({ session, token }: any): Promise<Session> {
            session.accessToken = token.accessToken;

            return session;
        }
    }
};

export default NextAuth(authOptions);
