/* eslint-env node */
const { defineConfig } = require('eslint-define-config')
const prettierConfig = require('@netwatch-global/prettier-config')
require('@rushstack/eslint-patch/modern-module-resolution')

module.exports = defineConfig({
  root: true,

  plugins: ['prettier', 'vuejs-accessibility', '@typescript-eslint', 'cypress'],

  extends: [
    'prettier',
    'plugin:storybook/recommended',
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

  env: {
    'cypress/globals': true,
  },

  rules: {
    'no-console': 'error',
    'indent': ['error', 2],
    'comma-dangle': ['error', 'always-multiline'],
    'space-before-function-paren': ['warn', 'never'],

    'vue/html-indent': ['error', 2],
    'vue/multiline-html-element-content-newline': 'off',
    'vue/multi-word-component-names': 'warn',

    'vue/max-attributes-per-line': 0,
    'vue/require-default-prop': 0,
    // 'vue/singleline-html-element-content-newline': 0,
    // 'vue/html-indent': 0,
    // 'vue/no-use-v-if-with-v-for': 0,
    // 'vue/no-v-for-template-key': 0,
    // 'vue/no-v-model-argument': 0,
    // 'vue/no-v-html': 0,
    'vue/no-multiple-template-root': 0,
    // 'vue/html-self-closing': 0,

    'prettier/prettier': ['error', prettierConfig],
    'vuejs-accessibility/label-has-for': 'off',
    'vuejs-accessibility/form-control-has-label': 'off',
    'space-before-function-paren': 'off',
    'no-console': ['error', { allow: ['warn', 'error'] }],
    '@typescript-eslint/no-unused-vars': [
      'error',
      {
        args: 'all',
        argsIgnorePattern: '^_',
        caughtErrors: 'all',
        caughtErrorsIgnorePattern: '^_',
        destructuredArrayIgnorePattern: '^_',
        varsIgnorePattern: '^_',
        ignoreRestSiblings: true,
      },
    ],
  },
})
