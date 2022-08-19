import { createApp } from 'vue';
//跨页面状态
import { createPinia } from 'pinia';
//项目入口
import App from './App.vue';
//路由
import router from "@/router/index";

//后端交互

//elementPlus
import ElementPlus from 'element-plus';

import './styles/index.scss';
import * as ElementPlusIconsVue from '@element-plus/icons-vue'

const app = createApp(App);

interface Component {
}

for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, <Component>component)
}
app.use(ElementPlus, { size: 'large', zIndex: 3000 });
app.use(createPinia());
app.use(router);
app.mount('#app');