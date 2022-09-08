import {createI18n} from "vue-i18n"
import {configStore} from "@/stores/config-store";
import zhCn from 'element-plus/lib/locale/lang/zh-cn'
import cn from '@/language/cn'
import en from '@/language/en'
import pinia from "@/utils/pinia";

const messages = {
    'en': {...en},
    'cn': {...zhCn, ...cn}
}
const i18n = createI18n({
    locale: configStore(pinia).language,
    globalInjection: true,
    messages
})

export default i18n