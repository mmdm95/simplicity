const path = require("path");
const common = require("./webpack.common");
const merge = require("webpack-merge");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = merge(common, {
    mode: "production",
    output: {
        filename: "[name].[contentHash].bundle.js",
        path: path.resolve(__dirname, "../public/build/")
    },
    plugins: [
        new MiniCssExtractPlugin({filename: "[name].[contentHash].css"}),
    ],
    module: {
        rules: [
            {
                test: /\.(scss|sass)$/,
                include: path.join(__dirname, "../resource/css/"),
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader, //3. Extract css into files
                        options: {
                            outputPath: path.resolve(__dirname, "../public/css/")
                        }
                    },
                    "css-loader", //2. Turns css into commonjs
                    "sass-loader" //1. Turns sass into css
                ]
            }
        ]
    },
    devtool: "source-map"
});
