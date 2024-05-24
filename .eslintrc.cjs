require('@rushstack/eslint-patch/modern-module-resolution');
const { defineConfig } = require('eslint-define-config');
const prettierConfig = require('./.prettierrc.cjs');

module.exports = defineConfig({
    root: true,
    extends: [
        'prettier',
        '@vue/eslint-config-typescript',
        'plugin:vue/vue3-essential',
        'plugin:vuejs-accessibility/recommended',
    ],
    parser: 'vue-eslint-parser',
    parserOptions: {
        parser: '@typescript-eslint/parser',
        ecmaVersion: 'latest',
        sourceType: 'module',
    },
    plugins: ['vuejs-accessibility', '@typescript-eslint', 'prettier'],
    rules: {
        'no-console': 'error',
        'comma-dangle': ['error', 'always-multiline'],
        'space-before-function-paren': ['warn', 'never'],
        'prettier/prettier': ['error', prettierConfig],

        'vue/html-indent': ['error', 4],
        'vue/multiline-html-element-content-newline': 'off',
        'vue/multi-word-component-names': 'off',

        'vue/require-default-prop': 0,
        'vue/singleline-html-element-content-newline': 0,
        // 'vue/html-indent': 0,
        // 'vue/no-use-v-if-with-v-for': 0,
        'vue/multi-word-component-names': 0,
        // 'vue/no-v-for-template-key': 0,
        // 'vue/no-v-model-argument': 0,
        'vue/no-v-html': 0,
        'vue/no-multiple-template-root': 0,
        // 'vue/html-self-closing': 0,

        'vuejs-accessibility/label-has-for': 'off',
        'vuejs-accessibility/form-control-has-label': 'off',

        'vue/max-attributes-per-line': [
            'error',
            {
                singleline: {
                    max: 3,
                },
                multiline: {
                    max: 0,
                },
            },
        ],
    },
});
