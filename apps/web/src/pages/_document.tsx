import { Html, Head, Main, NextScript } from 'next/document';

const Document = () => {
    return (
        <Html lang='en' data-theme='light'>
            <Head title='Ignite | Forum Platform Builder' />

            <body className='antialiased leading-normal h-screen overflow-x-hidden text-gray-600'>
                <Main />

                <NextScript />
            </body>
        </Html>
    );
};

export default Document;
