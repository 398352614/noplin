import {options, request} from "@/utils/axios";

//封装get,post,put,delete四种基础方法

/**
 * get请求
 * @param url
 * @param params
 * @param customOptions
 */
export function get(
    url: string,
    params: object,
    customOptions?: object,
) {
    return new Promise((resolve, reject) => {
        request.get(
            url, {
                params,
                ...Object.assign(options, customOptions),
            },
        ).then((res: object) => {
            resolve(res);
        }).catch((err: object) => {
            reject(err);
        });
    });
}

/**
 * post请求
 * @param url
 * @param params
 * @param customOptions
 */
export function post(
    url: string,
    params: object,
    customOptions?: any,
): Promise<any> {
    return new Promise((resolve, reject) => {
        request.post(
            url,
            params,
            Object.assign(options, customOptions),
        ).then(response => {
            resolve(response)
        }).catch(error => {
            reject(error)
        });
    });
}

/**
 * put请求
 * @param url
 * @param params
 * @param customOptions
 */
export function put(
    url: string,
    params: object,
    customOptions?: any,
) {
    return new Promise((resolve, reject) => {
        request.put(
            url,
            params,
            Object.assign(options, customOptions),
        ).then((res: object) => {
            resolve(res);
        }).catch((res: object) => {
            reject(res);
        });
    });
}

/**
 * delete请求
 * delete是保留字，所以用缩写del
 * @param url
 * @param params
 * @param customOptions
 */
export function del(
    url: string,
    params: object,
    customOptions?: any,
) {
    return new Promise((resolve, reject) => {
        request.delete(
            url, {
                ...params,
                ...Object.assign(options, customOptions),
            },
        ).then((res: object) => {
            resolve(res);
        }).catch((err: object) => {
            reject(err);
        });
    });
}