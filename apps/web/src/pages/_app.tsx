/* eslint-disable no-unused-vars */
import type { AppProps } from 'next/app';
// import '@/assets/css/globals.css';
import Layout from '@/layouts/default';
import { ChakraProvider } from '@chakra-ui/react';
import { defineConfig } from '@ignite/scribe';
import themeConfig from '../theme/config';
import { ReactNode } from 'react';
import { NextPage } from 'next';

export type NextPageWithLayout<P = any, IP = P> = NextPage<P, IP> & {
    getLayout?: (page: NextPage) => ReactNode;
};

type AppPropsWithLayout = AppProps & {
    Component: NextPageWithLayout;
};

defineConfig({
    apiUrl: process.env.NEXT_PUBLIC_API_URL
});

export default function App ({ Component, pageProps }: AppPropsWithLayout) {
    const getLayout: any = Component.getLayout ?? (
        (page: NextPageWithLayout) => <Layout>{page}</Layout>
    );

    return (
        <ChakraProvider theme={themeConfig}>
            {getLayout(<Component {...pageProps} />)}
        </ChakraProvider>
    );
}
