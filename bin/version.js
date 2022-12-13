const fs = require('fs-extra');
const replace = require('replace-in-file');

const pluginFiles = [
    'assets/**/*',
    'src/**/*',
    'includes/**/*',
    'templates/**/*',
    'tests/**/*',
    'awesome-post-view.php',
];

const { version } = JSON.parse(fs.readFileSync('package.json'));

replace({
    files: pluginFiles,
    from: /APV_SINCE/g,
    to: version,
});
