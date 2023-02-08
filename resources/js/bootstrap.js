import _ from 'lodash';
window._ = _;


import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import '/resources/scss/app.scss';
import * as bootstrap from 'bootstrap';

import * as Popper from '@popperjs/core';
window.Popper = Popper;
