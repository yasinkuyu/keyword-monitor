module.exports = {
    extends: [
       'plugin:vue/vue3-recommended',
       'eslint:recommended',
       'prettier',
       'plugin:prettier/recommended'
    ],
    rules: {
       'prettier/prettier': ['error', { singleQuote: true, semi: false }],
    },
 }