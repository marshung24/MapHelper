<?php

namespace marsapp\helper\mapping;

use marsapp\helper\myarray\ArrayHelper;

/**
 * Mapping Helper
 *
 * - Assist in processing the related actions of the mapping table.
 * - Provide unified data access functions to facilitate sharing throughout the program.
 *
 * @depends marsapp/
 * @author Mars.Hung <tfaredxj@gmail.com>
 */
class MapHelper
{

    /**
     * Map Cache Table
     *
     * @var array
     */
    protected static $_map = [];

    /**
     * *********************************************
     * ************** Public Function **************
     * *********************************************
     */


    /**
     * Init map cache table.
     *
     * @param string $mapName Map name for initialize, if value is null, initialize all.
     * @return MapHelper
     */
    public static function init(string $mapName = null)
    {
        if (is_null($mapName)) {
            self::$_map = [];
        } else {
            unset(self::$_map[$mapName]);
        }

        return new static();
    }

    /**
     * Add data to map cache table.
     * 
     * Need to specify the processing method.
     *
     * @param array $data Raw data, Want to save to map cache.
     * @param string $mapName Map name
     * @param array $indexBy Index list for grouping
     * @param string $indexType Type list: indexBy, groupBy, indexOnly
     * @return MapHelper
     */
    public static function add(array $data, string $mapName, array $indexBy, string $indexType = 'indexBy')
    {
        self::$_map[$mapName] = ArrayHelper::$indexType($data, $indexBy, 'return');
        return new static();
    }

    /**
     * Add raw data to map cache table.
     * 
     * Data will be directly stored in cache.
     *
     * @param array $data Raw data, Want to save to map cache.
     * @param string $mapName Map name
     * @return MapHelper
     */
    public static function addRaw(array $data, string $mapName)
    {
        self::$_map[$mapName] = $data;
        return new static();
    }

    /**
     * Sort map data.
     * 
     * When doing location point, it is necessary to sort in advance.
     * 
     * @param string $mapName Map name
     * @param string $type Sort type, Support 'ksort', 'krsort'
     * @return MapHelper
     */
    public static function sort(string $mapName, $type = 'ksort')
    {
        ArrayHelper::sortRecursive(self::$_map[$mapName], $type);
        return new static();
    }

    /**
     * Get data from map by index array.
     *
     * @param string $mapName Map name
     * @param array $indexBy Index list for search
     * @param bool $exception default false
     * @throws \Exception
     * @return array|mixed
     */
    public static function getMap(string $mapName, array $indexBy = [], $exception = false)
    {
        return ArrayHelper::getContent(self::$_map[$mapName], $indexBy, $exception);
    }

    /**
     * Get data by location point.
     *
     * When doing location point, it is necessary to sort in advance.
     * 
     * @param string $mapName Map name
     * @param array $indexBy array indexs before location point.
     * @param string $location location point field name
     * @return mixed
     */
    public static function location(string $mapName, array $indexBy, string $location)
    {
        $data = ArrayHelper::getContent(self::$_map[$mapName], $indexBy);
        $opt = ArrayHelper::getFallContent($data, $location, false);
        return $opt;
    }

    /**
     * Check if the map exists.
     *
     * @param string $mapName Map name
     * @return boolean
     */
    public static function hasMap(string $mapName)
    {
        return isset(self::$_map[$mapName]) && is_array(self::$_map[$mapName]);
    }

    /**
     * Get map list.
     *
     * @return array
     */
    public static function mapList()
    {
        return \array_keys(self::$_map) ?? [];
    }

    /**
     * ****************************************************
     * ************** Multiple Location Point **************
     * ****************************************************
     */

    /**
     * **********************************************
     * ************** Private Function **************
     * **********************************************
     */
}
