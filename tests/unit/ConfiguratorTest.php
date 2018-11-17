<?php

namespace Unforge\ConfiguratorToolkit;

/**
 * Class ConfiguratorTest
 * @package Unforge\ConfiguratorToolkit
 */
class ConfiguratorTest extends \PHPUnit_Framework_TestCase
{
    private $config_from_array = [];

    private $config_from_file;

    protected function setUp()
    {
        $this->config_from_array = [
            'locate1' => [
                'param1' => 'one',
                'param2' => 'two',
                'param3' => 'free',
            ],
            'locate2' => [
                'param_1' => 1,
                'param_2' => 2,
                'param_3' => [
                    'one' => 1,
                    'tro' => 'free'
                ],
            ],
            'temp_exist_object' => [
                'one' => 1,
                'two' => 'free'
            ]
        ];

        $this->config_from_file = __DIR__ . "/../resources/conf/config.php";
    }

    public function testInitConfigFromArray()
    {
        $configurator = new Configurator($this->config_from_array);
        $actual = $configurator->get('locate1');
        $expected = 'one';

        $this->assertEquals($expected, $actual->param1);
    }

    public function testInitConfigFromFile()
    {
        $configurator = new Configurator($this->config_from_file);
        $actual = $configurator->get('locate1');
        $expected = 'two';

        $this->assertEquals($expected, $actual->param2);
    }

    public function testGetLocateFromConfig()
    {
        $configurator = new Configurator($this->config_from_array);
        $actual = $configurator->get('locate1');
        $expected = [
            'param1' => 'one',
            'param2' => 'two',
            'param3' => 'free',
        ];

        $this->assertEquals($expected, $actual->getArrayCopy());
    }

    public function testGenScalarNestedParamFromConfig()
    {
        $configurator = new Configurator($this->config_from_array);
        $actual = $configurator->get('locate2');
        $expected = 2;

        $this->assertEquals($expected, $actual->param_2);
    }

    public function testGetArrayNestedParamFromConfig()
    {
        $configurator = new Configurator($this->config_from_array);
        $actual = $configurator->get('locate2');
        $expected = [
            'one' => 1,
            'tro' => 'free'
        ];

        $this->assertEquals($expected, $actual->param_3);
    }

    public function testGetExistConfigByObject()
    {
        $configurator = new Configurator($this->config_from_array);
        $object = new \Unforge\Tests\ConfiguratorToolkit\TempExistObject($configurator);
        $actual = $object->getConfig();
        $expected = [
            'one' => 1,
            'two' => 'free'
        ];

        $this->assertEquals($expected, $actual->getArrayCopy());
    }

    public function testGetNotExistConfigByObject()
    {
        $configurator = new Configurator($this->config_from_array);
        $object = new \Unforge\Tests\ConfiguratorToolkit\TempNotExistObject($configurator);
        $actual = $object->getConfig();
        $expected = [];

        $this->assertEquals($expected, $actual);
    }
}
