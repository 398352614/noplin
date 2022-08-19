import {defineStore} from 'pinia'

const key = "token";

export const tokenStore = defineStore('token', {
    state: () => (
        {
            "token": ""
        }
    ),
    actions: {
        get(): string {
            return localStorage.getItem(key) || sessionStorage.getItem(key) || '';
        },
        set(value: string, keep: boolean = false): void {
            keep ? localStorage.setItem(key, value) : sessionStorage.setItem(key, value);
        },
        remove(): void {
            localStorage.removeItem(key);
            sessionStorage.removeItem(key);
        }
    },
    getters: {
        header(): string {
            return 'Bearer ' + this.token;
        }
    }
})