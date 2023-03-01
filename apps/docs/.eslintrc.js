module.exports = {
    root: true,
    extends: ['custom'],
    plugins: ['mdx'],
    overrides: [
        {
            files: '*.mdx,*.md',
            extends: 'plugin:mdx/recommended'
        }
    ],
    settings: {
        'mdx/code-blocks': true
    },
    rules: {
        'indent': 'off'
    }
};
