# Excel@FIT2024
### xjezek19_systemForAutomaticHeatingControlWithMultipleHeatingSources
#### Lukáš Ježek

This system aims to design and implement a system like a smart home thermostat to control the house
heating and water heating of buildings divided into multiple zones with multiple heating sources. Of the
existing solutions, none allows efficient use of multiple sources with zoning, or the solution is expensive and
inefficient. In addition, the system offers extensibility to other IoT industries. This application specifically
controls the fireplace installation with a heat exchanger, gas boiler, and combined water tank. The system
can be controlled either remotely or locally. RaspberryPi 4B was chosen as the IoT platform with DS18B20
sensors for temperature measures and relay modules as actuators. The Control app is developed with web
technologies for cross-platform usage. Using Laravel with REST API for the backend part. And Vue.js for
the user interface. In addition, it is extendable for other IoT usage.

## Technologies

- Raspberry Pi 4B+
- ESP-01(S)
- Laravel 10
- Vue.js + Vuetify.js
