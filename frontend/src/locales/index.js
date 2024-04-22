import csLocale from '@/locales/cs'
import enLocale from '@/locales/en'
import { createI18n } from 'vue-i18n'

const allLocales = {
  cs: csLocale,
  en: enLocale
}

const i18n = createI18n({
  globalInjection: true,
  legacy: false,
  locale: 'cs',
  messages: allLocales
})

export default i18n
