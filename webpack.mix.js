/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */

const del = require('del');
const { mix } = require('laravel-mix');

del(['public/assets/css/**', 'public/assets/img/**', 'public/assets/fonts/**', 'public/assets/js/control-panel.js', 'public/assets/js/templates.js']);

mix.setPublicPath('public');

if (mix.inProduction) {
    mix.disableNotifications();
}

if (!mix.inProduction) {
	mix.sourceMaps();
}
	
mix.sass('resources/assets/sass/templates.scss', 'public/assets/css/templates.css');

mix.sass('resources/assets/sass/control-panel.scss', 'public/assets/css/control-panel.css');

mix.options({
	processCssUrls: false,
	postCss: [
		require('postcss-css-variables')()
	]
});
   
mix.js('resources/assets/js/templates.js', 'public/assets/js/templates.js');

mix.js('resources/assets/js/control-panel.js', 'public/assets/js/control-panel.js');

mix.copy('node_modules/font-awesome/fonts', 'public/assets/fonts');

mix.copy('resources/assets/fonts', 'public/assets/fonts');

mix.copy('resources/assets/img/focuspoint-cross.png', 'public/assets/img/focuspoint-cross.png');

mix.copy('resources/assets/img/apple-icon-57x57.png', 'public/assets/img/apple-icon-57x57.png');

mix.copy('resources/assets/img/apple-icon-72x72.png', 'public/assets/img/apple-icon-72x72.png');

mix.copy('resources/assets/img/apple-icon-144x144.png', 'public/assets/img/apple-icon-144x144.png');

mix.copy('resources/assets/img/delaneymethod.png', 'public/assets/img/delaneymethod.png');

mix.copy('resources/assets/img', 'public/assets/img');

mix.copy('resources/.htaccess', 'public/.htaccess');

mix.copy('resources/crossdomain.xml', 'public/crossdomain.xml');

mix.copy('resources/favicon.ico', 'public/favicon.ico');

mix.copy('resources/robots.txt', 'public/robots.txt');

mix.copy('resources/web.config', 'public/web.config');

mix.version();
