import {post} from "@/utils/request"

/**
 * 登录
 * @param data
 */
export function login(data: any) {
    return post('auth/login', data)
}