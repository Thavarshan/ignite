import type { AppProps } from 'next/app';
// import '@/assets/css/globals.css';
import Layout from '@/layouts/default';
import { ChakraProvider } from '@chakra-ui/react';
import { SessionProvider } from 'next-auth/react';
import { defineConfig } from '@ignite/scribe';

defineConfig({
    apiUrl: process.env.NEXT_PUBLIC_API_URL
});

export default function MyApp ({
    Component,
    pageProps: { session, ...pageProps }
}: AppProps) {
    return (
        <SessionProvider session={session}>
            <ChakraProvider>
                <Layout>
                    <Component {...pageProps} />
                </Layout>
            </ChakraProvider>
        </SessionProvider>
    );
}
