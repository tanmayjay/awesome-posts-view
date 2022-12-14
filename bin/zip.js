const fs = require('fs-extra');
const { exec } = require('child_process');
const util = require('util');
const chalk = require('chalk');
const asyncExec = util.promisify(exec);

const pluginFiles = [
    'assets/',
    'includes/',
    'languages/',
    'readme.txt',
    'awesome-post-view.php',
    'composer.json',
    'composer.lock',
];

const removeFiles = [
    'composer.json',
    'composer.lock',
];

const { version } = JSON.parse(fs.readFileSync('package.json'));

fs.removeSync('build/*.zip');

exec('rm *.zip', {
    cwd: 'build',
}, () => {
    const buildDir = 'build';
    const dest     = `${buildDir}/apv`;

    fs.removeSync(buildDir);

    const fileList = [...pluginFiles];

    fs.mkdirp(dest);

    fileList.forEach(file => {
        fs.copySync(file, `${dest}/${file}`);
    } );

    console.log('Finished copying files.');

    asyncExec('composer install --optimize-autoloader --no-dev', {
        cwd: dest,
    }, () => {
        console.log(`Installed composer packages in ${dest} directory.`);

        removeFiles.forEach(file => {
            fs.removeSync(`${dest}/${file}`);
        });

        const zipFile = `apv-${version}.zip`;

        console.log(`Making zip file ${zipFile}...`);

        asyncExec(`zip ${zipFile} apv -rq`, {
            cwd: buildDir,
        }, () => {
            fs.removeSync(dest);
            console.log(chalk.green(`${zipFile} is ready.`));
        } ).catch(error => {
            console.log(chalk.red(`Could not make ${zipFile}.`));
            console.log(error);
        } );
    }).catch(error => {
        console.log(chalk.red(`Could not install composer in ${dest} directory.`));
        console.log(error);
    });
});
