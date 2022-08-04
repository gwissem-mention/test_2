const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .enableStimulusBridge('./assets/controllers.json')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .enableSassLoader()
    .copyFiles([
        {from: './node_modules/@gouvfr/dsfr/dist/favicon', to: 'favicon/[path][name].[ext]'},
        {from: './node_modules/@gouvfr/dsfr/dist/fonts', to: 'fonts/[path][name].[ext]'},
        {from: './node_modules/@gouvfr/dsfr/dist/icons', to: 'icons/[path][name].[ext]'},
    ])
;

module.exports = Encore.getWebpackConfig();
