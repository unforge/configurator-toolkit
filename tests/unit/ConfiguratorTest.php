<?php

namespace Unforge\Toolkit;

use Unforge\ToolkitTests\ConfiguratorTests;

/**
 * Class ConfiguratorTest
 * @package Unforge\Toolkit
 */
class ConfiguratorTest extends \PHPUnit_Framework_TestCase
{
    private $config_from_array = [];

    private $config_from_file;

    protected function setUp()
    {
        $this->config_from_array = [
            'db' => [
                'host'      => '127.0.0.1',
                'port'      => 3306,
                'user'      => 'root',
                'password'  => 'free',
                'database'  => 'test',
            ],
            'redis' => [
                'host' => '127.0.0.1',
                'port' => 6379,
            ],
            'elastic_search' => [
                'hosts' => [
                    '127.0.0.1:9200',
                    '127.0.0.2:9200',
                    '127.0.0.3:9200',
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
        $actual = $configurator->get('db');
        $expected = '127.0.0.1';

        $this->assertEquals($expected, $actual->host);
    }

    public function testInitConfigFromFile()
    {
        $configurator = new Configurator($this->config_from_file);
        $actual = $configurator->get('db');
        $expected = 'test';

        $this->assertEquals($expected, $actual->database);
    }

    public function testGetLocateFromConfig()
    {
        $configurator = new Configurator($this->config_from_array);
        $actual = $configurator->get('db');
        $expected = [
            'host'      => '127.0.0.1',
            'port'      => 3306,
            'user'      => 'root',
            'password'  => 'free',
            'database'  => 'test',
        ];

        $this->assertEquals($expected, $actual->getArrayCopy());
    }

    public function testGenScalarNestedParamFromConfig()
    {
        $configurator = new Configurator($this->config_from_array);
        $actual = $configurator->get('redis');
        $expected = 6379;

        $this->assertEquals($expected, $actual->port);
    }

    public function testGetArrayNestedParamFromConfig()
    {
        $configurator = new Configurator($this->config_from_array);
        $actual = $configurator->get('elastic_search');
        $expected = [
            '127.0.0.1:9200',
            '127.0.0.2:9200',
            '127.0.0.3:9200',
        ];

        $this->assertEquals($expected, $actual->hosts);
    }

    public function testGetExistConfigByObject()
    {
        $configurator = new Configurator($this->config_from_array);
        $object = new ConfiguratorTests\TempExistObject($configurator);
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
        $object = new ConfiguratorTests\TempNotExistObject($configurator);
        $actual = $object->getConfig();
        $expected = [];

        $this->assertEquals($expected, $actual);
    }
}
