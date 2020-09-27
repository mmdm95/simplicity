const CleanWebpackPlugin = require("clean-webpack-plugin");
const OptimizeCssAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const ManifestPlugin = require('webpack-manifest-plugin');

module.exports = {
    entry: {
        main: "./resources/js/index.js",
        vendor: "./resources/js/vendor.js"
    },
    optimization: {
        minimizer: [
            new OptimizeCssAssetsPlugin(),
            new TerserPlugin(),
        ]
    },
    plugins: [
        new CleanWebpackPlugin(),
        new ManifestPlugin({
            basePath: './resources/js/'
        })
    ],
    module: {
        rules: [
            {
                test: /\.html$/,
                use: ["html-loader"]
            },
            {
                test: /\.(svg|png|jpg|gif)$/,
                use: {
                    loader: "file-loader",
                    options: {
                        name: "[name].[hash].[ext]",
                        outputPath: "image"
                    }
                }
            }
        ]
    }
};
