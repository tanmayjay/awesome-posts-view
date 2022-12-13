const path = require( 'path' );
const TerserJSPlugin = require( 'terser-webpack-plugin' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );
const { VueLoaderPlugin } = require( 'vue-loader' );

const entryPoints = {
    'admin': './src/admin/main.js',
};

const plugins = [
    new MiniCssExtractPlugin(
        {
            filename: ( { chunk } ) => {
                if ( chunk.name.match( /\/modules\// ) ) {
                    return process.env.NODE_ENV === 'production' ? `${ chunk.name.replace( '/js/', '/css/' ) }.min.css` : `${ chunk.name.replace( '/js/', '/css/' ) }.css`;
                }
                return process.env.NODE_ENV === 'production' ? '../css/[name].min.css' : '../css/[name].css';
            },
        }
    ),
    new VueLoaderPlugin(),
];



module.exports = {
    mode: process.env.NODE_ENV,
    entry: entryPoints,
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            '@': path.resolve('./src/'),
            'frontend': path.resolve('./src/frontend/'),
            'admin': path.resolve('./src/admin/'),
        },
        modules: [
            path.resolve('./node_modules'),
            path.resolve(path.join(__dirname, 'src/')),
        ]
    },
    output: {
        path: path.resolve( __dirname, './assets/js' ),
        filename: process.env.NODE_ENV === 'production' ? '[name].min.js' : '[name].js'
    },
    optimization: {
        minimizer: [ new TerserJSPlugin(), new CssMinimizerPlugin() ],
    },
    plugins,
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: require.resolve( 'babel-loader' ),
                        options: {
                            // Babel uses a directory within local node_modules
                            // by default. Use the environment variable option
                            // to enable more persistent caching.
                            cacheDirectory: process.env.BABEL_CACHE_DIRECTORY || true,
                        },
                    },
                ],
            },
            {
                test: /\.vue$/,
                use: {
                    loader: 'vue-loader',
                    options: {
                        extractCSS: true,
                    }
                }
            },
            {
                test: /\.less$/,
                use: [
                    'vue-style-loader',
                    'css-loader',
                    'less-loader',
                ],
            },
            {
                test: /\.css$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader'
                ],
            },
            {
                test: /\.(png|jpe?g|gif)$/i,
                loader: 'file-loader',
                options: {
                    name: '../images/dist/[name].[ext]',
                },
            },
        ],
    },
};
