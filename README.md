# 1. MapHelper
- Assist in processing the related actions of the mapping table.
- Provide unified data access functions to facilitate sharing throughout the program.


[![Latest Stable Version](https://poser.pugx.org/marsapp/maphelper/v/stable)](https://packagist.org/packages/marsapp/maphelper) [![Total Downloads](https://poser.pugx.org/marsapp/maphelper/downloads)](https://packagist.org/packages/marsapp/maphelper) [![Latest Unstable Version](https://poser.pugx.org/marsapp/maphelper/v/unstable)](https://packagist.org/packages/marsapp/maphelper) [![License](https://poser.pugx.org/marsapp/maphelper/license)](https://packagist.org/packages/marsapp/maphelper)

# 2. Outline
<!-- TOC -->

- [1. MapHelper](#1-maphelper)
- [2. Outline](#2-outline)
- [3. Installation](#3-installation)
  - [3.1. Composer Install](#31-composer-install)
  - [3.2. Include](#32-include)
- [4. Usage](#4-usage)
  - [4.1. Example](#41-example)
- [5. API Reference](#5-api-reference)
  - [5.1. init()](#51-init)
  - [5.2. add()](#52-add)
  - [5.3. addRaw()](#53-addraw)
  - [5.4. sort()](#54-sort)
  - [5.5. getMap()](#55-getmap)
  - [5.6. location()](#56-location)
  - [5.7. hasMap()](#57-hasmap)
  - [5.8. mapList()](#58-maplist)

<!-- /TOC -->



# 3. Installation
## 3.1. Composer Install
```
# composer require marsapp/maphelper
```

## 3.2. Include
Include composer autoloader before use.
```php
require __PATH__ . "vendor/autoload.php";
```

# 4. Usage
## 4.1. Example
Namespace use:
```php
// Use namespace
use marsapp\helper\mapping\MapHelper;

// Data
$data = [
    ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'],
    ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2'],
];

// Index by
MapHelper::add($data, 'userInfo', ['c_sn', 'u_no']);

// Get name by a110 => a001 => u_name
$name = MapHelper::getMap('userInfo', ['a110', 'a001', 'u_name']);
// $name = name1;
```

# 5. API Reference
## 5.1. init()
Init map cache table.

```php
init(string $mapName = null) : MapHelper
```
> Parameters
> - array $mapName: Map name for initialize, if value is null, initialize all.
> 
> Return Values
> - MapHelper


Example :
```php
// Use namespace
use marsapp\helper\mapping\MapHelper;

// Clear the Map cache with the specified name, like: userInfo.
MapHelper::init('userInfo');

//Clear all Map cache.
MapHelper::init();
```


## 5.2. add()
Add data to map cache table.
Need to specify the processing method.
```php
add(array $data, string $mapName, array $indexBy, string $indexType = 'indexBy') : MapHelper
```
> Parameters
> - array $data: Raw data, Want to save to map cache.
> - string $mapName: Map name
> - array $indexBy: Index list for grouping
> - string $indexType: Type list: indexBy, groupBy, indexOnly
> 
> Return Values
> - MapHelper


Example :
```php
// Use namespace
use marsapp\helper\mapping\MapHelper;

// Data
$data = [['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']];

// Store to map cache - Useing indexBy
MapHelper::add($data, 'map1', ['c_sn', 'u_sn'], 'indexBy');
// Get Map data
$result = MapHelper::getMap('map1');
var_export($result);
// Output: 
// array (
//   'a110' => 
//   array (
//     'b1' => 
//     array (
//       'c_sn' => 'a110',
//       'u_sn' => 'b1',
//       'u_no' => 'a001',
//       'u_name' => 'name1',
//     ),
//     'b2' => 
//     array (
//       'c_sn' => 'a110',
//       'u_sn' => 'b2',
//       'u_no' => 'b012',
//       'u_name' => 'name2',
//     ),
//   ),
// )


// Store to map cache - Useing groupBy
MapHelper::add($data, 'map2', ['c_sn', 'u_sn'], 'groupBy');
// Get Map data
$result = MapHelper::getMap('map2');
var_export($result);
// Output: 
// array (
//   'a110' => 
//   array (
//     'b1' => 
//     array (
//       0 => 
//       array (
//         'c_sn' => 'a110',
//         'u_sn' => 'b1',
//         'u_no' => 'a001',
//         'u_name' => 'name1',
//       ),
//     ),
//     'b2' => 
//     array (
//       0 => 
//       array (
//         'c_sn' => 'a110',
//         'u_sn' => 'b2',
//         'u_no' => 'b012',
//         'u_name' => 'name2',
//       ),
//     ),
//   ),
// )


// Store to map cache - Useing indexOnly
MapHelper::add($data, 'map3', ['c_sn', 'u_sn'], 'indexOnly');
// Get Map data
$result = MapHelper::getMap('map3');
var_export($result);
// Output: 
// array (
//   'a110' => 
//   array (
//     'b1' => '',
//     'b2' => '',
//   ),
// )
```


## 5.3. addRaw()
Add raw data to map cache table.
Data will be directly stored in cache.
```php
addRaw(array $data, string $mapName) : MapHelper
```
> Parameters
> - array $data: Raw data, Want to save to map cache.
> - string $mapName: Map name
> 
> Return Values
> - MapHelper


Example :
```php
// Use namespace
use marsapp\helper\mapping\MapHelper;

// Data
$data = [['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']];

// Store to map cache
MapHelper::addRaw($data, 'map1');
// Get Map data
$result = MapHelper::getMap('map1');
var_export($result);
// Output: 
// [['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']]
```


## 5.4. sort()
Sort map data.
When doing location point, it is necessary to sort in advance.
```php
sort(string $mapName, $type = 'ksort') : MapHelper
```
> Parameters
> - string $mapName: Map name
> - string $type: Sort type, Support 'ksort', 'krsort'
> 
> Return Values
> - Returns the resulting array.


Example :
```php
// Use namespace
use marsapp\helper\mapping\MapHelper;

$data = ['1111' => 'name1', '3333' => 'name3', '2222' => 'name2'];

// Add map
MapHelper::addRaw($data, 'testMap');


// Sort Map and output
$result = MapHelper::sort('testMap', 'ksort')->getMap('testMap');
var_export($result);
// Output: ['1111' => 'name1', '2222' => 'name2', '3333' => 'name3']


// Sort Map and output
$result = MapHelper::sort('testMap', 'krsort')->getMap('testMap');
var_export($result);
// Output: ['3333' => 'name3', '2222' => 'name2', '1111' => 'name1']

```


## 5.5. getMap()
Data re-index by keys
```php
getMap(string $mapName, array $indexBy = [], $exception = false) : mixed
```
> Parameters
> - string $mapName: Map name
> - array $indexBy: Index list for search
> - bool $exception: default false
>
> Throw Exception
> Return Values
> - array|mixed


Example :
```php
// Use namespace
use marsapp\helper\mapping\MapHelper;

$data = ['1111' => 'name1', '3333' => 'name3', '2222' => 'name2'];

// Add map
MapHelper::addRaw($data, 'testMap');
$result = MapHelper::getMap('testMap');
var_export($result);
// Output: ['1111' => 'name1', '3333' => 'name3', '2222' => 'name2']
```


## 5.6. location()
Get data by location point.
When doing location point, it is necessary to sort in advance.
```php
location(string $mapName, array $indexBy, string $location) : mixed
```
> Parameters
> - string $mapName: Map name
> - array $indexBy: array indexs before location point.
> - string $location: Location point field name
> 
> Return Values
> - mixed


Example :
```php
// Use namespace
use marsapp\helper\mapping\MapHelper;

// Data
$data = ['lv1' => ['lv2' => ['2011-05-06' => '11', '2012-01-01' => '12', '2015-09-16' => '15']]];

// Add map
MapHelper::addRaw($data, 'testMap');

// Get data by location point.
$result = MapHelper::location('testMap', ['lv1', 'lv2'], '2011-12-05');
var_export($result);
// Output: 11

// Get data by location point.
$result = MapHelper::location('testMap', ['lv1', 'lv2'], '2012-12-05');
var_export($result);
// Output: 12
```


## 5.7. hasMap()
Data re-index by keys
```php
hasMap(string $mapName) : boolean
```
> Parameters
> - array $mapName: Map name.
> 
> Return Values
> - boolean


Example :
```php
// Use namespace
use marsapp\helper\mapping\MapHelper;

// Add map
MapHelper::addRaw([1, 2, 3, 4], 'map1');

// Determine whether the target map table exists
$mapName = 'map1';
if (MapHelper::hasMap($mapName)) {
    echo $mapName . ' Exist.';
} else {
    echo $mapName . ' Not exist.';
}
```


## 5.8. mapList()
Data re-index by keys
```php
mapList() : array
```
> Return Values
> - array.


Example :
```php
// Use namespace
use marsapp\helper\mapping\MapHelper;

// Add map
MapHelper::addRaw([1, 2, 3, 4], 'map1');
MapHelper::addRaw(['a', 'b'], 'map2');

// Get map name list and print it.
$mapList = MapHelper::mapList();
var_export($mapList);
// output: ['map1', 'map2']
```

