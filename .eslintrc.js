module.exports = {
    extends: [
        'eslint:recommended',
        'plugin:vue/recommended',
        'plugin:vue/base',
        'plugin:vue/essential',
        'plugin:vue/strongly-recommended',
    ],
    rules: {
        'no-unused-vars': 'off',
        'vue/no-unused-vars': 'off',
        'vue/html-indent': 'off',
        'vue/no-v-html': 'off',
        'vue/html-self-closing': 'off',
    }
}
