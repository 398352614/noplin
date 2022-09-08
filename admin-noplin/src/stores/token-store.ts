import {defineStore} from 'pinia'

const key = "token";

export const tokenStore = defineStore(key, {
    state: () => (
        {
            "token": ""
        }
    ),
    actions: {
        get(): string {
            return this.token || localStorage.getItem(key) || '';
        },
        set(value: string, keep: boolean = false): void {
            this.token = value;
            keep && localStorage.setItem(key, value);
        },
        remove(): void {
            this.token = "";
            localStorage.removeItem(key);
        }
    }
})