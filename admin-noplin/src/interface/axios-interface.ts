import {AxiosRequestConfig, AxiosResponse, AxiosResponseHeaders} from "axios";

export interface AxiosApi extends AxiosResponse{
    config: AxiosConfig
    data: {
        code: number
        data: Array<any> | object
        msg: string
    }
    headers: AxiosResponseHeaders
    status: number
    statusText: string
    request?: any

}

//追加属性
interface AxiosConfig extends AxiosRequestConfig{
    showTips: boolean
    customTipsSwitch: boolean
    tipsContent: string
}