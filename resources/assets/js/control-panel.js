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

require('classlist-polyfill');

require('jquery-ui/ui/widgets/sortable');

require('jquery-ui/ui/disable-selection');

require('bootstrap');

require('air-datepicker');

require('air-datepicker/dist/js/i18n/datepicker.en');

require('jquery-focuspoint');

require('../plugins/fileselect/fileselect');

require('../plugins/redactor/v3/redactor');

/* require('../plugins/redactor/v2/plugins/source'); */

require('../plugins/redactor/v3/plugins/table');

/* require('../plugins/redactor/v2/plugins/definedlinks'); */

require('../plugins/redactor/v3/plugins/alignment');

require('../plugins/redactor/v3/plugins/fullscreen');

require('../plugins/redactor/v3/plugins/filemanager/filemanager');

require('../plugins/redactor/v3/plugins/imagemanager');

require('../plugins/redactor/v3/plugins/video');

require('../plugins/redactor/v3/plugins/inlinestyle/inlinestyle');

require('../plugins/redactor/v3/plugins/fontcolor');

require('../plugins/redactor/v3/plugins/properties');

require('../plugins/redactor/v3/plugins/textexpander');

require('../plugins/redactor/v3/plugins/specialchars');

/*
require('../plugins/redactor/v2/plugins/codemirror');

window.CodeMirror = require('../plugins/codemirror/codemirror');

require('../plugins/codemirror/modes/htmlmixed');

require('../plugins/codemirror/modes/javascript');

require('../plugins/codemirror/modes/css');

require('../plugins/codemirror/modes/xml');
*/

require('../plugins/password-strength');

require('../plugins/datatables/datatables');

require('../plugins/datatables/datatables-bootstrap');

require('../plugins/delaneymethod/browse/index');

require('../plugins/delaneymethod/cms/library');

require('../plugins/delaneymethod/cms/control-panel');
