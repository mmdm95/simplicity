const path = require("path");
const common = require("./webpack.common");
const merge = require("webpack-merge");

module.exports = merge(common, {
    mode: "development",
    output: {
        filename: "[name].bundle.js",
        path: path.resolve(__dirname, "../public/build/")
    },
    module: {
        rules: [
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    "style-loader", //4. Inject styles into DOM
                    "css-loader", //3. Turns css into commonjs
                    'postcss-loader', //2. Use autoprefixer plugin
                    "sass-loader" //1. Turns sass into css
                ]
            }
        ]
    },
    devtool: 'inline-source-map',
});
