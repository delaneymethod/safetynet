/**
 * @link      https://www.delaneymethod.com/cms
 * @copyright Copyright (c) DelaneyMethod
 * @license   https://www.delaneymethod.com/cms/license
 */
 
window._ = require('lodash');

window.$ = window.jQuery = require('jquery');

window.Tether = require('tether');

window.Popper = require('popper.js').default;

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.FastClick = require('fastclick');

window.Clipboard = require('clipboard');

window.plyr = require('plyr');

require('moment');

require('classlist-polyfill');

require('bootstrap');

require('fullcalendar');

require('jquery-focuspoint');

require('../plugins/fileselect/fileselect');

require('../plugins/password-strength');

require('../plugins/delaneymethod/cms/library');

require('../plugins/delaneymethod/cms/templates');
