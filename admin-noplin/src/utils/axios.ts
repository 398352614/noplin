import axios from "axios";
import {ElMessage} from "element-plus";
import router from "@/router/index";
import {tokenStore} from "@/stores/token-store";
import {configStore} from "@/stores/config-store";

let baseApi: string;
switch (location.hostname) {
    case 'http://localhost:3000': //开发服
        baseApi = 'http://localhost:11112/admin/';
        break;
    default:
        baseApi = 'http://localhost:11112/admin/';
        break;
}

export const request = axios.create({
    baseURL: baseApi,
    timeout: 1000 * 120, // 超时
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    }
})

//默认配置
export function options() {
    return {
        showTips: true, // 是否开启提示消息
        customTipsSwitch: false, // 是否开启自定义消息
        tipsContent: '', // 自定义的提示内容
    }
}

//请求拦截器
request.interceptors.request.use(withToken);

//返回拦截器
request.interceptors.response.use(success, error);

//返回处理
function success(res: any): object | void {
    switch (res.data.code) {
        case 200://成功
            if (res.config.showTips && res.config.customTipsSwitch) {
                ElMessage({
                    // message: i18n.t('success'),
                    message: res.config.tipsContent,
                    type: 'success',
                    showClose: false,
                })
                break;
            } else {
                return res.data.data;
            }
        case 4001://表单验证失败
            ElMessage({
                message: res.data.msg,
                type: 'error',
                showClose: false,
            });
            break
        case 2001://用户认证失败
            ElMessage({
                // message: i18n.t('LoginExpiredPleaseLogAgain'),
                message: 'LoginExpiredPleaseLogAgain',
                type: 'error',
                showClose: false,
            });
            tokenStore().remove();
            setTimeout(() => {
                router.replace({
                    path: '/login',
                    query: {
                        redirect: router.currentRoute.value.path,
                    },
                }).then(r => {
                    console.log(r)
                })
            }, 1000);
            break
        case 1000:
            ElMessage({
                message: res.data.msg,
                type: 'error',
                showClose: false,
            });
            break;
        default:
            break;
    }
}

function error(error: any) {
    const status = error.response && error.response.status; // 捕获错误状态码
    if (status == 500) {
        ElMessage({
            message: '网络错误',
            type: 'error',
            showClose: false,
        });
    } else {
        ElMessage({
            message: error,
            type: 'error',
            showClose: false,
        });
    }
    return Promise.reject(error);
}

function withToken(config: any) {
    config.headers.Authorization = 'Bearer ' + tokenStore().get()
    config.headers.language = configStore().language
    return config;
}