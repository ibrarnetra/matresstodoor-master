const mix = require("laravel-mix");
const glob = require("glob");
const path = require("path");
const ReplaceInFileWebpackPlugin = require("replace-in-file-webpack-plugin");
const rimraf = require("rimraf");
const WebpackRTLPlugin = require("webpack-rtl-plugin");
const del = require("del");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Remove existing generated assets from public folder
del.sync([
    "public/metronic/css/*",
    "public/metronic/js/*",
    "public/metronic/media/*",
    "public/metronic/plugins/*",
]);

// Build 3rd party plugins css/js
mix.sass(
    "resources/metronic/webpack/plugins/plugins.scss",
    "public/metronic/plugins/global/plugins.bundle.css"
)
    .then(() => {
        // remove unused preprocessed fonts folder
        rimraf(path.resolve("public/metronic/fonts"), () => {});
        rimraf(path.resolve("public/metronic/images"), () => {});
    })
    .sourceMaps(!mix.inProduction())
    // .setResourceRoot('./')
    .options({ processCssUrls: false })
    .js(
        ["resources/metronic/webpack/plugins/plugins.js"],
        "public/metronic/plugins/global/plugins.bundle.js"
    );

// Build css/js
mix.sass(
    "resources/metronic/sass/style.scss",
    "public/metronic/css/style.bundle.css",
    {
        sassOptions: { includePaths: ["node_modules"] },
    }
)
    // .options({processCssUrls: false})
    .js(
        "resources/metronic/webpack/scripts.demo5.js",
        "public/metronic/js/scripts.bundle.js"
    );

// Build custom 3rd party plugins
(glob.sync("resources/metronic/plugins/custom/**/*.js") || []).forEach(
    (file) => {
        mix.js(
            file,
            `public/metronic/${file
                .replace("resources/metronic/", "")
                .replace(".js", ".bundle.js")}`
        );
    }
);
(glob.sync("resources/metronic/plugins/custom/**/*.scss") || []).forEach(
    (file) => {
        mix.sass(
            file,
            `public/metronic/${file
                .replace("resources/metronic/", "")
                .replace(".scss", ".bundle.css")}`
        );
    }
);

// Build css pages (single page use)
(glob.sync("resources/metronic/sass/pages/**/!(_)*.scss") || []).forEach(
    (file) => {
        file = file.replace(/[\\/]+/g, "/");
        mix.sass(
            file,
            file
                .replace("resources/metronic/sass", "public/metronic/css")
                .replace(/\.scss$/, ".css")
        );
    }
);

var extendedFiles = [];
// Extend custom js files for laravel
(glob.sync("resources/extended/js/**/*.js") || []).forEach((file) => {
    var output = `public/metronic/${file.replace("resources/extended/", "")}`;
    mix.js(file, output);
    extendedFiles.push(output);
});

// Js pages (single page use)
(glob.sync("resources/metronic/js/custom/**/*.js") || []).forEach((file) => {
    var output = `public/metronic/${file.replace("resources/metronic/", "")}`;
    if (extendedFiles.indexOf(output) === -1) {
        mix.js(file, output);
    }
});

// Copy media folder
mix.copyDirectory("resources/metronic/media", "public/metronic/media");

(glob.sync("resources/metronic/sass/themes/**/!(_)*.scss") || []).forEach(
    (file) => {
        file = file.replace(/[\\/]+/g, "/");
        mix.sass(
            file,
            file
                .replace("resources/metronic/sass", "public/metronic/css")
                .replace(/\.scss$/, ".css")
        );
    }
);

mix.webpackConfig({
    plugins: [
        new ReplaceInFileWebpackPlugin([
            {
                // rewrite font paths
                dir: path.resolve("public/metronic/plugins/global"),
                test: /\.css$/,
                rules: [
                    {
                        // fontawesome
                        search: /url\((\.\.\/)?webfonts\/(fa-.*?)"?\)/g,
                        replace: "url(./fonts/@fortawesome/$2)",
                    },
                    {
                        // flaticon
                        search: /url\(("?\.\/)?font\/(Flaticon\..*?)"?\)/g,
                        replace: "url(./fonts/flaticon/$2)",
                    },
                    {
                        // flaticon2
                        search: /url\(("?\.\/)?font\/(Flaticon2\..*?)"?\)/g,
                        replace: "url(./fonts/flaticon2/$2)",
                    },
                    {
                        // keenthemes fonts
                        search: /url\(("?\.\/)?(Ki\..*?)"?\)/g,
                        replace: "url(./fonts/keenthemes-icons/$2)",
                    },
                    {
                        // lineawesome fonts
                        search: /url\(("?\.\.\/)?fonts\/(la-.*?)"?\)/g,
                        replace: "url(./fonts/line-awesome/$2)",
                    },
                    {
                        // socicons
                        search: /url\(("?\.\.\/)?font\/(socicon\..*?)"?\)/g,
                        replace: "url(./fonts/socicon/$2)",
                    },
                    {
                        // bootstrap-icons
                        search: /url\(.*?(bootstrap-icons\..*?)"?\)/g,
                        replace: "url(./fonts/bootstrap-icons/$1)",
                    },
                ],
            },
        ]),
        // Uncomment this part for RTL version
        /*new WebpackRTLPlugin({
            filename: '[name].rtl.css',
            options: {},
            plugins: [],
        })*/
    ],
    ignoreWarnings: [
        {
            module: /esri-leaflet/,
            message: /version/,
        },
    ],
});

// Webpack.mix does not copy fonts, manually copy
(
    glob.sync("resources/metronic/plugins/**/*.+(woff|woff2|eot|ttf)") || []
).forEach((file) => {
    var folder = file.match(/resources\/plugins\/(.*?)\//)[1];
    mix.copy(
        file,
        `public/metronic/plugins/global/fonts/${folder}/${path.basename(file)}`
    );
});
(
    glob.sync(
        "node_modules/+(@fortawesome|socicon|line-awesome|bootstrap-icons)/**/*.+(woff|woff2|eot|ttf)"
    ) || []
).forEach((file) => {
    var folder = file.match(/node_modules\/(.*?)\//)[1];
    mix.copy(
        file,
        `public/metronic/plugins/global/fonts/${folder}/${path.basename(file)}`
    );
});

// Optional: Output datatables.net
mix.scripts(
    [
        "node_modules/datatables.net/js/jquery.dataTables.js",
        "node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js",
        "node_modules/datatables.net-responsive/js/dataTables.responsive.min.js",
        "node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js",
        "node_modules/datatables.net-scroller/js/dataTables.scroller.min.js",
        "resources/metronic/js/vendors/plugins/datatables.init.js",
    ],
    "public/metronic/plugins/custom/datatables/datatables.bundle.js"
);
mix.styles(
    [
        "node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css",
        "node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css",
        "node_modules/datatables.net-scroller-bs4/css/scroller.bootstrap4.min.css",
    ],
    "public/metronic/plugins/custom/datatables/datatables.bundle.css"
);

// Optional: Output fullcalendar
mix.scripts(
    [
        "node_modules/fullcalendar/main.js",
        "node_modules/fullcalendar/locales-all.min.js",
    ],
    "public/metronic/plugins/custom/fullcalendar/fullcalendar.bundle.js"
);
mix.styles(
    ["node_modules/fullcalendar/main.min.css"],
    "public/metronic/plugins/custom/fullcalendar/fullcalendar.bundle.css"
);
