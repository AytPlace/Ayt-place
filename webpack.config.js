var Encore = require('@symfony/webpack-encore');
var $ = require('jquery');
Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('recipient', './assets/js/recipient.js')
    .addEntry('client', './assets/js/client.js')
    .addEntry('admin', './assets/js/admin.js')

    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.scss)
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();
