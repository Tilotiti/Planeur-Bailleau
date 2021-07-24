const Encore = require('@symfony/webpack-encore');
const path = require('path');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .addEntry('app', './assets/js/app.js')
    .addEntry('index', './assets/js/index.js')
    .addEntry('admin', './assets/js/admin.js')
    .addEntry('contact', './assets/js/contact.js')

    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[hash:8].[ext]'
    })

    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableIntegrityHashes(Encore.isProduction())
    .autoProvidejQuery()

    .configureDevServerOptions(options => {
        options.https = {
            pfx: path.join(process.env.HOME, '.symfony/certs/default.p12'),
        }

        delete options.client;
    })

    .enableSassLoader((options) => {
        options.sourceMap = true;
        options.sassOptions = {
            outputStyle: options.outputStyle,
            sourceComments: !Encore.isProduction(),
        };
        delete options.outputStyle;
    }, {})

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
;

module.exports = Encore.getWebpackConfig();
