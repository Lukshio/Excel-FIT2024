import { en } from 'vuetify/locale'

export default {
  $vuetify: { ...en },
  navDrawer: {
    home: 'Overview',
    temperatures: 'Temperatures',
    zoneIndex: 'Zones',
    deviceIndex: 'Devices',
    manual: 'Manual',
    graphs: 'Graphs',
    systemLog: 'System Logs',
    tempLog: 'Temperature Logs',
    settings: 'Settings',
    users: 'Users',
    logout: 'Logout'
  },
  globals: {
    confirmBtn: 'Confirm',
    saveBtn: 'Save',
    cancelBtn: 'Cancel',
    on: 'Turn on',
    off: 'Turn off',
    requiredForm: 'Fields marked with * are required',
    showDetails: 'Show Details'
  },
  errors: {
    duplicateZone: 'Zone already exists',
    duplicateDevice: 'Device already exists'
  },
  zones: {
    heading: 'Zone Overview',
    addZoneButton: 'Add Zone',
    switchAutoHeating: 'auto heating?',
    form: {
      name: 'Name *',
      zoneType: 'Zone Type *',
      temp: 'Temperature *',
      mainSensor: 'Main Sensor',
      relay: 'Control Relay',
      auto: 'Control Automatically',
      kpi: 'KPI',
      user: 'Selected user',
      program: 'Heat only by program'
    },
    deleteForm: {
      text: 'Do you really want to delete zone {msg}?'
    }
  },
  devices: {
    heading: 'Devices',
    newDevice: 'New Device',
    ds18b20: 'Find DS18B20 Sensors',
    tabs: {
      sensors: 'Sensors',
      relays: 'Relays'
    },
    form: {
      name: 'Name *',
      uuid: 'UUID/URL *',
      deviceType: 'Device Type *',
      pin: 'Pinout',
      protocol: 'Protocol *',
      description: 'Description',
      heatingSource: 'Gas boiler control',
      heatingWaterSensor: 'Main heating water sensor',
      invalidCombination: 'Invalid Combination',
      heatingWaterSensorPresent: 'Other sensor is selected',
      heatingSourcePresent: 'Other source is selected',
      overheatPresent: 'Other sensor is selected',
      overHeat: 'Overheat protection sensor',
      fireplaceOpen: 'Always open this relay when in fireplace mode'
    },
    deleteForm: {
      text: 'Do you really want to delete device {msg}?'
    }
  },
  temperatures: {
    heading: 'Temperatures',
    refreshButton: 'Refresh'
  },
  manual: {
    heading: 'Manual control',
    alert: 'Auto Mode',
    error: 'Unable to retrieve relay state'
  },
  systemLog: {
    heading: 'System logs'
  },
  tempLog: {
    heading: 'Temperatures logs'
  },
  operationalMode: {
    title: 'Operational Mode',
    change: 'Change mode',
    krb: 'Fireplace',
    kotel: 'Gas boiler',
    combined: 'Combined',
    off: 'Turned off',
    error: 'Error'
  },
  electricHeating: 'Water boiler electric heating',
  users: {
    users: 'Users',
    newUser: 'New User',
    editUser: 'Edit User',
    name: 'Name',
    email: 'Email',
    role: 'Role',
    links: 'links',
    search: 'Search',
    form: {
      password: 'Password'
    },
    deleteConfirm: 'Are you sure you want to delete user {msg} ?'
  }
}
