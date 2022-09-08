import {defineStore} from 'pinia'

const key = "config";

export const configStore = defineStore(key, {
    state: () => ({
            "language": "cn"
        })
})