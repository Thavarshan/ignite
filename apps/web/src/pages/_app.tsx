/* eslint-disable no-unused-vars */
import type { AppProps } from 'next/app';
import Layout from '@/layouts/default';
import { ChakraProvider } from '@chakra-ui/react';
import { defineConfig } from '@ignite/scribe';
import themeConfig from '@/theme/config';
import { ReactNode } from 'react';
import { NextPage } from 'next';
import '@/assets/css/globals.css';
import '@fontsource/inter/variable.css';
import AuthProvider from '@/providers/AuthProvider';

export type NextPageWithLayout<P = any, IP = P> = NextPage<P, IP> & {
    getLayout?: (page: NextPage) => ReactNode;
};

type AppPropsWithLayout = AppProps & {
    Component: NextPageWithLayout;
};

defineConfig({
    url: process.env.NEXT_PUBLIC_URL,
    apiUrl: process.env.NEXT_PUBLIC_API_URL,
    apiKey: process.env.NEXT_PUBLIC_API_KEY
});

const App = ({ Component, pageProps }: AppPropsWithLayout) => {
    const getLayout: any = Component.getLayout ?? (
        (page: NextPageWithLayout) => <Layout>{page}</Layout>
    );

    return (
        <ChakraProvider theme={themeConfig}>
            <AuthProvider>
                {getLayout(<Component {...pageProps} />)}
            </AuthProvider>
        </ChakraProvider>
    );
};

export default App;
