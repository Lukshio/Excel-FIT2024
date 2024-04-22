export function keysToCamelCase (anyValue) {
  if (Array.isArray(anyValue)) {
    return anyValue.map(element => keysToCamelCase(element))
  } else if (anyValue === Object(anyValue)) {
    const newObj = {}
    Object.keys(anyValue).forEach(key => {
      const newKeyArr = key.split('_')
      for (let i = 1; i < newKeyArr.length; i++) {
        newKeyArr[i] = newKeyArr[i][0].toUpperCase() + newKeyArr[i].slice(1)
      }
      const newKey = newKeyArr.join('')
      newObj[newKey] = keysToCamelCase(anyValue[key])
    })
    return newObj
  } else {
    return anyValue
  }
}

export function keysToSnakeCase (anyValue) {
  if (Array.isArray(anyValue)) {
    return anyValue.map(element => keysToSnakeCase(element))
  } else if (anyValue === Object(anyValue)) {
    const newObj = {}
    Object.keys(anyValue).forEach(key => {
      const newKey = key.split(/(?=[A-Z])/).join('_').toLowerCase()
      newObj[newKey] = keysToSnakeCase(anyValue[key])
    })
    return newObj
  } else {
    return anyValue
  }
}
