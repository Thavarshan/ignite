/** @type {import('prettier').Config} */
const config = {
    plugins: ['prettier-plugin-organize-imports', 'prettier-plugin-tailwindcss'],
    singleQuote: true,
    semi: true,
    trailingComma: 'all',
    printWidth: 80,
    tabWidth: 4,
    htmlWhitespaceSensitivity: 'css',
    singleAttributePerLine: false,
    tailwindFunctions: ['clsx', 'cn', 'cva'],
    tailwindStylesheet: 'resources/css/app.css',
    overrides: [
        {
            files: '**/*.yml',
            options: {
                tabWidth: 2,
            },
        },
    ],
};

export default config;
