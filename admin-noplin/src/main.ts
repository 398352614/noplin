import { createApp } from 'vue';//框架
import { createPinia } from 'pinia';//跨页面状态
import App from './App.vue';//项目入口
import router from "@/router/index";//路由
import ElementPlus from 'element-plus';//elementPlus
import './styles/index.scss';//自定义样式

const app = createApp(App);

app.use(ElementPlus, { size: 'large', zIndex: 3000 });
app.use(createPinia());
app.use(router);
app.mount('#app');