import type { AppProps } from 'next/app';
import '@/assets/css/globals.css';
import Layout from '@/layouts/default';

export default function MyApp ({ Component, pageProps }: AppProps) {
    return (
        <Layout>
            <Component {...pageProps} />
        </Layout>
    );
}
