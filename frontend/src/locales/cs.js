import { cs } from 'vuetify/locale'

export default {
  $vuetify: { ...cs },
  navDrawer: {
    home: 'Přehledy',
    temperatures: 'Teploty',
    zoneIndex: 'Zóny',
    deviceIndex: 'Zařízení',
    manual: 'Manuál',
    graphs: 'Grafy',
    systemLog: 'Systém logy',
    tempLog: 'Teplotní logy',
    settings: 'Nastavení',
    users: 'Uživatelé',
    logout: 'Odhlásit se'
  },
  globals: {
    confirmBtn: 'Potvrdit',
    saveBtn: 'Uložit',
    cancelBtn: 'Zrušit',
    on: 'Zapnout',
    off: 'Vypnout',
    requiredForm: 'Pole označené * jsou povinné',
    showDetails: 'Zobrazit detaily'
  },
  errors: {
    duplicateZone: 'Zóna již existuje',
    duplicateDevice: 'Zařízení již existuje'
  },
  zones: {
    heading: 'Přehled zón',
    addZoneButton: 'Přidat zónu',
    switchAutoHeating: 'automatické vyhřívání?',
    form: {
      name: 'Název *',
      zoneType: 'Typ zóny *',
      temp: 'Teplota *',
      mainSensor: 'Hlavní senzor',
      relay: 'Ovládací relé',
      auto: 'Ovládat automaticky',
      kpi: 'KPI',
      user: 'Přidělený uživatel',
      program: 'Vytápět dle programu'
    },
    deleteForm: {
      text: 'Opravdu chcete smazat zónu {msg} ?'
    }
  },
  devices: {
    heading: 'Zařízení',
    newDevice: 'Nové zařízení',
    ds18b20: 'Najít DS18B20 senzory',
    tabs: {
      sensors: 'Senzory',
      relays: 'Relé'
    },
    form: {
      name: 'Název *',
      uuid: 'UUID/URL *',
      deviceType: 'Typ zařízení *',
      pin: 'Pinout',
      protocol: 'Protokol *',
      description: 'Popis',
      heatingSource: 'Ovládání kotle',
      heatingWaterSensor: 'Hlavní senzor teploty topné vody',
      invalidCombination: 'Špatná kombinace typu a protokolu',
      heatingWaterSensorPresent: 'Je zvolený jiný senzor',
      heatingSourcePresent: 'Ovládání je již přiřazené',
      overheatPresent: 'Je zvolený jiný senzor',
      overHeat: 'Senzor pro tepelnou ochranu',
      fireplaceOpen: 'Vždy sepnout toto relé při topení krbem'
    },
    deleteForm: {
      text: 'Opravdu chcete smazat zařízení {msg} ?'
    }
  },
  temperatures: {
    heading: 'Teploty',
    refreshButton: 'Obnovit'
  },
  manual: {
    heading: 'Manuální ovládání',
    alert: 'Auto mód',
    error: 'Nepodařilo se zjistit stav relé'
  },
  systemLog: {
    heading: 'Systémové záznamy'
  },
  tempLog: {
    heading: 'Teplotní záznamy'
  },
  operationalMode: {
    title: 'Mód provozu',
    change: 'Změnit mód',
    krb: 'Krbová vložka',
    kotel: 'Kotel',
    combined: 'Kombinované',
    off: 'Vypnuto',
    error: 'Chyba'
  },
  electricHeating: 'Ohřev bojleru elektřinou',
  users: {
    users: 'Uživatelé',
    newUser: 'Nový uživatel',
    editUser: 'Editovat uživatele',
    name: 'Jméno',
    email: 'Email',
    role: 'Role',
    links: 'Akce',
    search: 'Vyhledávání',
    form: {
      password: 'Password'
    },
    deleteConfirm: 'Opravdu chcete smazat uživatele {msg} ?'
  }
}
