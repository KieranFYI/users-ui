import {createApp} from 'vue';
import lodash from 'lodash';
import axios from 'axios';

import Logging from './components/Logging';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp({});

app.config.globalProperties.$axios = axios;
app.config.globalProperties.$lodash = lodash;

app.component('Logging', Logging);
app.mount('#vuejs-users-tabs-logging');
