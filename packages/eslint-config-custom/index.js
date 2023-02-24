module.exports = {
    parserOptions: {
        ecmaVersion: 6,
        parser: '@typescript-eslint/parser',
        sourceType: 'module',
        ecmaFeatures: {
            jsx: false,
            experimentalObjectRestSpread: true
        }
    },
    plugins: [
        '@typescript-eslint'
    ],
    extends: [
        'turbo',
        'eslint:recommended',
        'next',
        'plugin:@typescript-eslint/eslint-recommended',
        'plugin:@typescript-eslint/recommended'
    ],
    rules: {
        indent: ['error', 4, { SwitchCase: 2 }],
        quotes: ['warn', 'single'],
        '@typescript-eslint/no-explicit-any': 'off',
        semi: 'off',
        '@typescript-eslint/semi': ['error'],
        'comma-dangle': ['error', 'never'],
        'turbo/no-undeclared-env-vars': 'off',
        '@next/next/no-html-link-for-pages': 'off',
        'next/no-html-link-for-pages': 'off',
        'no-html-link-for-pages': 'off',
        'react/jsx-key': 'off',
        'no-unused-vars': ['error', { 'argsIgnorePattern': '^_' }],
        '@typescript-eslint/no-unused-vars': ['error', { 'argsIgnorePattern': '^_' }],
        'no-console': 'error'
    }
};
