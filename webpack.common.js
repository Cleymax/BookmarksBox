const path = require('path');
const webpack = require('webpack');

module.exports = [{
  entry: {
    app: [path.resolve(__dirname, './resources/js/app.js')],
  },
  output: {
    path: path.resolve(__dirname, './public/js/'),
    publicPath: '/js/',
    filename: '[name].js',
    clean: true,
  },
  plugins: [
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
    }),
  ],
  module: {
    rules: [
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader'],
      },
      {
        test: /\.scss$/,
        use: ['style-loader', 'css-loader', 'sass-loader'],
      },
      /* {
        test: /\.js$/,
        loader: 'eslint-loader',
        exclude: /node_modules|vendor\.js/,
        enforce: 'pre',
      },*/
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
          },
        },
      },
      {
        test: /\.(png|jpg|gif)$/,
        loader: 'url-loader',
        options: {
          name: '[name].[ext]?[hash]',
          limit: 8192,
        },
      },
    ],
  },
}];
