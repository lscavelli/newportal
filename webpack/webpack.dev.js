var webpackMerge = require('webpack-merge');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var commonConfig = require('./webpack.common.js');
var helpers = require('./helpers');
var path = require('path');

module.exports = webpackMerge(commonConfig, {
  devtool: 'cheap-module-eval-source-map',
  
  output: {
    //path: helpers.root('dist'),
    path: path.resolve('public/assets/'),
    publicPath: 'http://lara.dev:8080/',
    filename: '[name].js',
    chunkFilename: '[id].chunk.js',
  },
  
  plugins: [
    new ExtractTextPlugin('[name].css')
  ],

  // Con Webpack è possibile utilizzare l’hot-reload,
  // che ci consente di avere il file .js compilato in tempo reale,
  // a fronte di cambiamenti - per attivarlo webpack-dev-server --inline --hot

  devServer: {
    hot: true, // attiva l' hot reload
    inline: true, // utilizza il metodo inline per hmr
    host: "lara.dev",
    port: 8080,
    contentBase: path.join(__dirname, "public"),
    historyApiFallback: true,
    stats: 'minimal',
    watchOptions: {
      poll: false
    }
  }
});
