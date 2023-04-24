const Encore = require("@symfony/webpack-encore");

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
        {from: "./node_modules/@gouvfr/dsfr/dist/favicon", to: "favicon/[path][name].[hash:8].[ext]"},
        {from: "./node_modules/@gouvfr/dsfr/dist/fonts", to: "fonts/[path][name].[hash:8].[ext]"},
        {from: "./node_modules/@gouvfr/dsfr/dist/icons", to: "icons/[path][name].[hash:8].[ext]"},
    ])
    // https://symfony.com/doc/current/frontend/encore/dev-server.html
    .configureDevServerOptions(options => {
        // https://symfony.com/doc/current/frontend/encore/virtual-machine.html#allow-external-access
        options.host = '0.0.0.0';

        // https://www.spiriit.com/blog/nginx-webpack-dev-serveur-browsersync-dans-une-stack-docker/
        options.proxy = {
            '*': 'http://nginx-citoyen',
            secure: false,
            changeOrigin: true,
            autoRewrite: true,
            ignorePath: false,
            // https://github.com/webpack/webpack-dev-server/issues/793#issuecomment-444165866
            headers: {
                Connection: 'Keep-Alive',
            }
        }

        // https://symfony.com/doc/current/frontend/encore/virtual-machine.html#fix-invalid-host-header-issue
        options.allowedHosts = [".pel.localhost"];

        // https://medium.com/@pbrecska/how-to-run-https-webpack-dev-server-in-dockervirtual-enviroment-9238b90fba4c
        options.headers = {
            "Access-Control-Allow-Origin": "*",
        }

        // https://stackoverflow.com/a/68671321
        // https://stackoverflow.com/a/75365211
        options.watchFiles = {
            paths: ['templates/**/*.twig', 'assets/**/*.ts', 'assets/**/*.sass'],
        }
    })

;

module.exports = Encore.getWebpackConfig();
