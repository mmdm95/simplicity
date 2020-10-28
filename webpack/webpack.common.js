const path = require("path");
const webpack = require('webpack');
/**
 * if you want to change a path please consider that
 * this plugin will delete all files inside that path
 */
const {CleanWebpackPlugin} = require("clean-webpack-plugin");
const OptimizeCssAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const ManifestPlugin = require('webpack-manifest-plugin');
const dotenv = require('dotenv').config({
    path: path.join(__dirname, '.js.env')
});

module.exports = {
    entry: {
        font: path.resolve(__dirname, "../resource/js/font.js"),
        main: path.resolve(__dirname, "../resource/js/index.js"),
        vendor: path.resolve(__dirname, "../resource/js/vendor.js")
    },
    optimization: {
        minimizer: [
            new OptimizeCssAssetsPlugin({
                cssProcessorOptions: {
                    map: {
                        inline: false,
                        annotation: true,
                    },
                },
            }),
            new TerserPlugin({
                // Use multi-process parallel running to improve the build speed
                // Default number of concurrent runs: os.cpus().length - 1
                parallel: true,
                // Enable file caching
                cache: true,
                sourceMap: true,
            }),
        ],
        splitChunks: {
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendors',
                    chunks: 'all'
                }
            }
        }
    },
    plugins: [
        new ManifestPlugin({
            fileName: "manifest.json"
        }),
        new CleanWebpackPlugin(),
        new webpack.DefinePlugin( {
            "process.env": dotenv.parsed
        } ),
    ],
    module: {
        rules: [
            {
                test: /\.(bmp|svg|png|jpe?g|gif)$/,
                use: {
                    loader: "file-loader",
                    options: {
                        name: "[name].[hash].[ext]",
                        outputPath: "../image/"
                    }
                }
            },
            {
                test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[hash].[ext]',
                            outputPath: "../fonts/"
                        }
                    }
                ]
            },
            {
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                        plugins: ['@babel/plugin-transform-runtime']
                    }
                }
            }
        ]
    }
};
