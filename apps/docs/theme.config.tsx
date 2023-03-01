import React from 'react';
import { DocsThemeConfig } from 'nextra-theme-docs';

const config: DocsThemeConfig = {
    useNextSeoProps () {
        return {
            titleTemplate: '%s – Ignite'
        };
    },

    head: (
        <>
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta property="og:title" content="Ignite" />
            <meta property="og:description" content="Forum platform builder" />
        </>
    ),

    logo: <span>Ignite</span>,

    project: {
        link: 'https://github.com/Thavarshan/ignite'
    },

    chat: {
        link: 'https://discord.com'
    },

    docsRepositoryBase: 'https://github.com/Thavarshan/ignite',

    footer: {
        text: 'Ignite Forum Platform Documentation'
    }
};

export default config;
