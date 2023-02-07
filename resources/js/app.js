import './bootstrap';

import { createApp } from 'vue/dist/vue.esm-bundler';
import store from '@/store';
import Router from '@/router';

const app = createApp({})

app.use(Router)
app.use(store)
app.mount('#app')
