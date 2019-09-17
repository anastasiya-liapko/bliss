const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const autoprefixer = require('autoprefixer');

const NODE_ENV = process.env.NODE_ENV || 'development';

module.exports = {
  context: path.resolve(__dirname, './../../src/shop-admin-panel/'),
  entry: {
    'incoming-orders': './incoming-orders.jsx',
    'delivering-orders': './delivering-orders.jsx',
    'issued-orders': './issued-orders.jsx',
    'failed-orders': './failed-orders.jsx',
    header: './header.jsx',
  },
  output: {
    path: path.resolve(__dirname, './../../public/assets/shop-admin-panel/'),
    filename: '[name].js',
  },
  optimization: {
    splitChunks: {
      cacheGroups: {
        js: {
          test: /\.jsx?$/,
          name: 'commons',
          chunks: 'initial',
          minChunks: 2,
        },
      },
    },
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /(node_modules)/,
        use: [
          {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env', '@babel/preset-react'],
            },
          },
          'eslint-loader',
        ],
      },
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              plugins: () => autoprefixer(),
            },
          },
        ],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'bundle.css',
    }),
  ],
};
