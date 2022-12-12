const wpvuei18n = require( 'wp-vue-i18n' );

wpvuei18n.makepot( {
    exclude: [
        'assets/*',
        'build/*',
        'node_modules/*',
        'vendor/*',
        '.github/*',
        '.php_cs'
    ],
    mainFile: 'awesome-post-view.php',
    domainPath: '/languages/',
    potFile: 'apv.pot',
    type: 'wp-plugin',
    potHeaders: {
        'report-msgid-bugs-to': 'https://github.com/tanmayjay/awesome-posts-view/issues/new',
        'language-team': 'LANGUAGE <EMAIL@ADDRESS.COM>',
        'poedit': true,
        'x-poedit-keywordslist': true
    }
} );
