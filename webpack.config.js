const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const isDev = process.env.NODE_ENV === 'dev'

module.exports = {
  plugins: [
    new MiniCssExtractPlugin({
      filename: '../css/[name].css'
    })
  ],
  entry: [
    path.resolve(__dirname, './resources/js/app.js'),
    path.resolve(__dirname, './resources/scss/app.scss')
  ],
  output: {
    path: path.resolve(__dirname, './public/js/'),
    publicPath: '/js/',
    filename: '[name].js',
    clean: true
  },
  devtool: isDev ? 'cheap-module-source-map' : false,
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.js$/,
        exclude: /node_modules/,
        use: ['eslint-loader']
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      {
        test: /\.s[ac]ss$/,
        use: [
          'style-loader',
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              esModule: false
            }
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: isDev,
              url: false,
              importLoaders: 1
            }
          },
          {
            loader: 'postcss-loader'
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: isDev,
              sassOptions: {
                outputStyle: isDev ? 'expanded' : 'compressed'
              }
            }
          }
        ]
      }
    ]
  }
}
