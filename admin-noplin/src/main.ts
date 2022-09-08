import {createApp} from 'vue'; //框架
import {createPinia} from 'pinia'; //跨页面状态
import App from './App.vue'; //项目入口
import router from "./router/index"; //路由
import ElementPlus from 'element-plus'; //elementPlus
import './styles/index.scss'; //自定义样式
import i18n from './utils/i18n'

const app = createApp(App);

app.use(ElementPlus, {size: 'large', zIndex: 3000})
    .use(createPinia())
    .use(router)
    .use(i18n)
    .mount('#app');