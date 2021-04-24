const { merge } = require('webpack-merge');
const TerserPlugin = require('terser-webpack-plugin');
const common = require('./webpack.common.js');

module.exports = common.map(
  (config) => merge(config, {
    mode: 'production',
    devtool: false,
    optimization: {
      minimize: true,
      minimizer: [
        new TerserPlugin({
          extractComments: {
            condition: /^\**!|@preserve|@license|@cc_on/i,
            filename: (fileData) => `${fileData.filename}.LICENSE.txt`,
            banner: (licenseFile) => `License information can be found in ${licenseFile}`,
          },
        }),
      ],
    },
  }),
);
