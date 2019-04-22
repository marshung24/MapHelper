<?php

namespace marsapp\helper\test\timeperiod;

use \Exception;
use PHPUnit\Framework\TestCase;
use marsapp\helper\mapping\MapHelper;

/**
 * Test for MapHelper - PHP Unit
 * 
 * @author Mars.Hung <tfaredexj@gmail.com>
 */
class MapHelperTest extends TestCase
{

    /**
     * *********************************************
     * ************** Public Function **************
     * *********************************************
     */

    /**
     * Test add() & getMap() & init() & hasMap()
     */
    public function testAdd()
    {
        // 初始化
        MapHelper::init();

        $templetes = self::addData();
        $expecteds = self::addExpected();

        // Run test by loop
        foreach ($templetes as $k => $templete) {
            $mapName = $templete[1];
            // Test add() & getMap()
            $result = \call_user_func_array(['\marsapp\helper\mapping\MapHelper', 'add'], $templete)->getMap($mapName);
            $this->assertEquals($expecteds[$k], $result);

            // Test init() & hasMap()
            $res = MapHelper::hasMap($mapName);
            $this->assertEquals($res, true);

            if ($k === 0) {
                // init 1
                $res = MapHelper::init($mapName)->hasMap($mapName);
                $this->assertEquals($res, false);
            } else {
                // init all
                $res = MapHelper::init()->hasMap($mapName);
                $this->assertEquals($res, false);
            }
        }
    }

    /**
     * Test addRaw & getMap
     */
    public function testAddRaw()
    {
        // 初始化
        MapHelper::init();

        $templetes = self::addRawData();
        $expecteds = self::addRawExpected();

        // Run test by loop
        foreach ($templetes as $k => $templete) {
            $mapName = $templete[1];
            // The same
            $result = \call_user_func_array(['\marsapp\helper\mapping\MapHelper', 'addRaw'], $templete)->getMap($mapName);

            $this->assertEquals($expecteds[$k], $result);
        }
    }

    /**
     * Test sort -  ksort,krsort
     */
    public function testSort()
    {
        // 初始化
        MapHelper::init();

        $templetes = self::addSortData();
        $expecteds = self::addSortExpected();

        // Run test by loop
        foreach ($templetes as $k => $templete) {
            $mapName = $templete[1];
            // add map
            \call_user_func_array(['\marsapp\helper\mapping\MapHelper', 'addRaw'], $templete);

            // Test ksort
            $result = MapHelper::sort($mapName, 'ksort')->getMap($mapName);
            $this->assertEquals($expecteds[$k]['ksort'], $result);

            // Test krsort
            $result = MapHelper::sort($mapName, 'krsort')->getMap($mapName);
            $this->assertEquals($expecteds[$k]['krsort'], $result);
        }
    }

    /**
     * Test getMap
     */
    public function testGetMap()
    {
        // 初始化
        MapHelper::init();

        $data = ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];
        $mapName = 'testMap';

        MapHelper::addRaw($data, $mapName);

        $output = MapHelper::getMap($mapName);
        // $output: ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']];
        $this->assertEquals($output, ['user' => ['name' => 'Mars', 'birthday' => '2000-01-01']]);

        /**
         * Test: get by Single layer key
         */
        // By array
        $output = MapHelper::getMap($mapName, ['user']);
        // $output: ['name' => 'Mars', 'birthday' => '2000-01-01'];
        $this->assertEquals($output, ['name' => 'Mars', 'birthday' => '2000-01-01']);

        /**
         * Test: get by Multi-layer key
         */
        // By array
        $output = MapHelper::getMap($mapName, ['user', 'name']);
        // $outpu: Mars
        $this->assertEquals($output, 'Mars');

        /**
         * Test: get nothing
         */
        // Return empty array
        $output = MapHelper::getMap($mapName, ['user', 'name', 'aaa']);
        // $outpu: null
        $this->assertEquals($output, null);
        // Throw exception
        try {
            $output = 'NoException';
            MapHelper::getMap($mapName, ['user', 'name', 'aaa'], true);
        } catch (Exception $e) {
            $output = 'Exception';
        }
        $this->assertEquals($output, 'Exception');
    }

    /**
     * Test location
     */
    public function testLocation()
    {
        // 初始化
        MapHelper::init();
        $templetes = self::addLocationData();
        $expecteds = self::addLocationExpected();

        // Run test by loop
        foreach ($templetes as $k => $templete) {
            $mapName = $templete[1];
            // add map
            \call_user_func_array(['\marsapp\helper\mapping\MapHelper', 'addRaw'], $templete);

            // Text location point
            foreach ($expecteds[$k] as $row) {
                $result = MapHelper::location($mapName, $row['indexBy'], $row['location']);
                $this->assertEquals($row['result'], $result);
            }
        }
    }

    /**
     * Test getMapList
     */
    public function testMapList()
    {
        // 初始化
        MapHelper::init();

        $templetes = self::addMapListData();
        $expecteds = self::addMapListExpected();

        // Run test by loop
        foreach ($templetes as $k => $templete) {
            \call_user_func_array(['\marsapp\helper\mapping\MapHelper', 'addRaw'], $templete);
        }

        $result = MapHelper::mapList();
        $this->assertEquals($expecteds, $result);
    }

    /**
     * ****************************************************
     * ************** Data Templete Function **************
     * ****************************************************
     */

    /**
     * Test Data - add
     * @return array
     */
    protected static function addData()
    {
        return [
            [[['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']], 'nameMapIndexBy', ['c_sn', 'u_sn'], 'indexBy'],
            [[['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']], 'nameMapGroupBy', ['c_sn', 'u_sn'], 'groupBy'],
            [[['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']], 'nameMapIndexOnly', ['c_sn', 'u_sn'], 'indexOnly'],
        ];
    }

    /**
     * Expected Data - add
     * @return array
     */
    protected static function addExpected()
    {
        return [
            ['a110' => ['b1' => ['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1'], 'b2' => ['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']]],
            ['a110' => ['b1' => [['c_sn' => 'a110', 'u_sn' => 'b1', 'u_no' => 'a001', 'u_name' => 'name1']], 'b2' => [['c_sn' => 'a110', 'u_sn' => 'b2', 'u_no' => 'b012', 'u_name' => 'name2']]]],
            ['a110' => ['b1' => '', 'b2' => '']],
        ];
    }

    /**
     * Test Data - addRaw
     * @return array
     */
    protected static function addRawData()
    {
        return [
            [['1111' => 'name1', '2222' => 'name2', '3333' => 'name3'], 'nameMap'],
            [['2011-05-06' => '11', '2012-01-01' => '12', '2015-09-16' => '15'], 'locationPointMap'],
        ];
    }

    /**
     * Expected Data - addRaw
     * @return array
     */
    protected static function addRawExpected()
    {
        return [
            ['1111' => 'name1', '2222' => 'name2', '3333' => 'name3'],
            ['2011-05-06' => '11', '2012-01-01' => '12', '2015-09-16' => '15'],
        ];
    }

    /**
     * Test Data - sort
     * @return array
     */
    protected static function addSortData()
    {
        return [
            [['1111' => 'name1', '3333' => 'name3', '2222' => 'name2'], 'nameMap'],
            [['2012-01-01' => '12', '2011-05-06' => '11', '2015-09-16' => '15'], 'locationPointMap'],
        ];
    }

    /**
     * Expected Data - sort
     * @return array
     */
    protected static function addSortExpected()
    {
        return [
            ['ksort' => ['1111' => 'name1', '2222' => 'name2', '3333' => 'name3'], 'krsort' => ['3333' => 'name3', '2222' => 'name2', '1111' => 'name1']],
            ['ksort' => ['2011-05-06' => '11', '2012-01-01' => '12', '2015-09-16' => '15'], 'krsort' => ['2015-09-16' => '15', '2012-01-01' => '12', '2011-05-06' => '11']],
        ];
    }

    /**
     * Test Data - location
     * @return array
     */
    protected static function addLocationData()
    {
        return [
            [['1111' => 'name1', '2222' => 'name2', '3333' => 'name3'], 'nameMap'],
            [['2011-05-06' => '11', '2012-01-01' => '12', '2015-09-16' => '15'], 'locationPointMap'],
            [['lv1' => ['lv2' => ['2011-05-06' => '11', '2012-01-01' => '12', '2015-09-16' => '15']]], 'locationPointMap2'],
        ];
    }

    /**
     * Expected Data - location
     * @return array
     */
    protected static function addLocationExpected()
    {
        return [
            [
                ['indexBy' => [], 'location' => '2222', 'result' => 'name2'],
            ],
            [
                ['indexBy' => [], 'location' => '2011-06-01', 'result' => '11'],
                ['indexBy' => [], 'location' => '2012-06-01', 'result' => '12'],
            ],
            [
                ['indexBy' => ['lv1', 'lv2'], 'location' => '2011-06-01', 'result' => '11'],
                ['indexBy' => ['lv1', 'lv2'], 'location' => '2012-06-01', 'result' => '12'],
            ],
        ];
    }

    /**
     * Test Data - mapList
     * @return array
     */
    protected static function addMapListData()
    {
        return [
            [['1111' => 'name1', '2222' => 'name2', '3333' => 'name3'], 'nameMap'],
            [['2011-05-06' => '11', '2012-01-01' => '12', '2015-09-16' => '15'], 'locationPointMap'],
        ];
    }

    /**
     * Expected Data - mapList
     * @return array
     */
    protected static function addMapListExpected()
    {
        return [
            'nameMap', 'locationPointMap',
        ];
    }
}
