import webpack from 'webpack';
import path from 'path';
import CopyWebpackPlugin from 'copy-webpack-plugin';

module.exports = {
  context: path.resolve(__dirname, 'src'),
  entry: './index.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'bundle.min.js',
  },
  resolve: {
    extensions: ['.mjs', '.jsx', '.js', '.json'],
    modules: [
      path.resolve(__dirname, 'node_modules'),
      'node_modules',
    ],
  },

  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        use: 'babel-loader',
      },
    ],
  },
  optimization: {
    minimize: true
  },
  plugins: ([
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV),
    }),
    new CopyWebpackPlugin([
      { from: path.resolve(__dirname, 'node_modules/graphql-voyager/dist/voyager.worker.js') },
      { from: path.resolve(__dirname, 'node_modules/graphql-voyager/dist/voyager.css') },
      { from: path.resolve(__dirname, 'src/container.css') },
    ]),
  ]),
  externals: {
    jquery: 'jQuery',
    drupal: 'Drupal',
  },
};