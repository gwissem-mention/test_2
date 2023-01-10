const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore
    .setOutputPath("public/build/")
    .setPublicPath("/build")
    .addEntry("app", "./assets/app.ts")
    .addStyleEntry("pages_home", "./assets/styles/styles.sass")
    .enableTypeScriptLoader()
    .enableStimulusBridge("./assets/controllers.json")
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel((config) => {
        config.plugins.push("@babel/plugin-proposal-class-properties");
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = "usage";
        config.corejs = 3;
    })
    .enableSassLoader()
    .copyFiles([
        {from: "./assets/images", to: "images/[path][name].[hash:8].[ext]"},
    ])
;

module.exports = Encore.getWebpackConfig();
