import { defineConfig } from 'vitepress';

// https://vitepress.dev/reference/site-config
export default defineConfig({
    title: 'NetWatch Global',
    description: 'API Reference & Guide',
    outDir: './../public/docs/',
    base: '/docs/',

    themeConfig: {
        nav: [
            { text: 'Platform', link: 'http://minerva.test' },
            { text: 'Guide', link: '/guide/index', activeMatch: '/guide/' },
            { text: 'Web', link: '/web/index', activeMatch: '/web/' },
            { text: 'API', link: '/api/index', activeMatch: '/api/' },
            { text: 'Console', link: '/console/index', activeMatch: '/console/' },
            {
                text: '0.1.0',
                items: [
                    {
                        text: 'Changelog',
                        link: '/CHANGELOG.md'
                    },
                    {
                        text: 'Contributing',
                        link: '/CONTRIBUTING.md'
                    },
                    {
                        text: 'Conduct',
                        link: '/CODE_OF_CONDUCT.md'
                    }
                ]
            }
        ],

        sidebar: {
            '/guide/': [
                {
                    text: 'Guide',
                    base: '/guide/',
                    items: [
                        { text: 'Introduction', link: 'index' },
                        {
                            text: 'Developer',
                            collapsed: false,
                            items: [
                                { text: 'Introduction', link: 'developer/index' },
                                { text: 'Middleware', link: 'developer/middleware' },
                                { text: 'Internal HTTP Requests', link: 'developer/internal-http-requests' },
                                { text: 'Global Properties', link: 'developer/global-props' },
                                { text: 'Environment Variables', link: 'developer/environment-variables' },
                            ]
                        },
                    ]
                },
            ],
            '/web/': [
                {
                    text: 'Web Views',
                    base: '/web/',
                    items: [
                        { text: 'Introduction', link: 'index' },
                        { text: 'Overview', link: 'overview' },
                        { text: 'Utilities', link: 'utilities' },
                        {
                            text: 'Authentication',
                            collapsed: false,
                            items: [
                                { text: 'Introduction', link: 'auth/index' },
                                { text: 'Login', link: 'auth/login' },
                                { text: 'Register', link: 'auth/register' },
                                { text: 'Forgot Password', link: 'auth/forgot-password' },
                                { text: 'Reset Password', link: 'auth/reset-password' },
                                { text: 'Two Factor Authentication', link: 'auth/two-factor-authentication' },
                                { text: 'Authorisation', link: 'auth/authorisation' }
                            ]
                        },
                        {
                            text: 'Users',
                            collapsed: false,
                            items: [
                                { text: 'Users', link: 'users/index' },
                                { text: 'Location', link: 'users/location' },
                                { text: 'Settings', link: 'users/settings' },
                            ]
                        },
                        {
                            text: 'Organisations',
                            collapsed: false,
                            items: [
                                { text: 'Organisations', link: 'organisations/index' },
                                { text: 'Teams', link: 'organisations/teams' },
                                { text: 'Members', link: 'organisations/members' },
                                { text: 'Invitations', link: 'organisations/invitations' },
                                { text: 'Location', link: 'organisations/location' },
                                { text: 'Settings', link: 'organisations/settings' },
                            ]
                        },
                        {
                            text: 'Cases',
                            collapsed: false,
                            items: [
                                { text: 'Cases', link: 'cases/index' },
                                { text: 'New Case', link: 'cases/create' },
                                { text: 'New Case: Police Report', link: 'cases/police-report' },
                                { text: 'Case', link: 'cases/show' },
                                { text: 'Create Case', link: 'cases/store' },
                            ]
                        },
                    ]
                },
            ],
            '/api/': [
                {
                    text: 'API Reference',
                    base: '/api/',
                    items: [
                        { text: 'Introduction', link: 'index' },
                        {
                            text: 'Authentication',
                            collapsed: false,
                            items: [
                                { text: 'Getting Started', link: 'auth/index' },
                                { text: 'Authorisation', link: 'auth/authorisation' }
                            ]
                        },
                        {
                            text: 'Users',
                            collapsed: false,
                            items: [
                                { text: 'Basics', link: 'users/index' },
                                { text: 'Settings', link: 'users/settings' }
                            ]
                        },
                        {
                            text: 'Organisations',
                            collapsed: false,
                            items: [
                                { text: 'Basics', link: 'organisations/index' },
                                { text: 'Invitations', link: 'organisations/invitations' },
                                { text: 'Teams', link: 'organisations/teams' },
                                { text: 'Members', link: 'organisations/members' },
                                { text: 'Settings', link: 'organisations/settings' }
                            ]
                        },
                        {
                            text: 'Police',
                            collapsed: false,
                            items: [
                                { text: 'Find by Area', link: 'police/find' },
                                { text: 'Reports', link: 'police/reports' },
                            ]
                        },
                        {
                            text: 'Locations',
                            collapsed: false,
                            items: [
                                { text: 'Lookup Locations', link: 'locations/lookup' },
                            ]
                        },
                    ]
                },
            ],
            '/console/': [
                {
                    text: 'Console Commands',
                    base: '/console/',
                    items: [
                        { text: 'Introduction', link: 'index' },
                    ]
                },
            ],
        },

        socialLinks: [
            { icon: 'github', link: 'https://github.com/netwatch-global/minerva' }
        ]
    }
});
