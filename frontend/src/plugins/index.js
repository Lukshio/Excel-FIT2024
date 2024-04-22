/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

// Plugins
import { loadFonts } from './webfontloader'
import vuetify from './vuetify'
import i18n from '@/locales'

export function registerPlugins (app) {
  loadFonts()
  app
    .use(i18n)
    .use(vuetify)
}
