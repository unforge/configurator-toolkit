<?php
/**
 * This file is part of the Configurator library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ronam Unstirred (unforge.coder@gmail.com)
 * @license http://opensource.org/licenses/MIT MIT
 */

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

    public function testOffsetExists()
    {
        $configurator = new Configurator($this->config_from_array);

        $actual = $configurator->offsetExists('db');
        $this->assertTrue($actual);

        $actual = $configurator->offsetExists('db-dev');
        $this->assertFalse($actual);
    }

    public function testOffsetGet()
    {
        $configurator = new Configurator($this->config_from_array);

        $actual = $configurator->offsetGet('redis');
        $expected = '127.0.0.1';
        $this->assertStringEndsWith($expected, $actual->host);

        $actual = $configurator->offsetGet('db-dev');
        $expected = [];
        $this->assertEquals($expected, $actual);
    }

    public function testOffsetSet()
    {
        $configurator = new Configurator($this->config_from_array);

        $configurator->get('db');
        $configurator->offsetSet('db', ['sql_mode' => 'ALLOW_INVALID_DATES']);
        $actual = $configurator->get('db');
        $expected = [
            'sql_mode' => 'ALLOW_INVALID_DATES',
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'root',
            'password' => 'free',
            'database' => 'test',
        ];
        $this->assertEquals($expected, $actual);

        $configurator->offsetSet('db-dev', ['sql_mode' => 'ALLOW_INVALID_DATES']);
        $actual = $configurator->offsetGet('db-dev');
        $actual = json_decode(json_encode($actual), true);
        $expected = [
            'sql_mode' => 'ALLOW_INVALID_DATES'
        ];
        $this->assertEquals($expected, $actual);
    }

    public function testOffsetUnset()
    {
        $configurator = new Configurator($this->config_from_array);

        $actual = $configurator->get('redis');
        $actual = json_decode(json_encode($actual), true);
        $expected = [
            'host' => '127.0.0.1',
            'port' => 6379,
        ];
        $this->assertEquals($expected, $actual);

        $configurator->offsetSet('redis', ['ttl' => 10]);
        $actual = $configurator->get('redis');
        $actual = json_decode(json_encode($actual), true);
        $expected = [
            'ttl' => 10,
            'host' => '127.0.0.1',
            'port' => 6379,
        ];
        $this->assertEquals($expected, $actual);

        $configurator->offsetUnset('redis');
        $actual = $configurator->get('redis');
        $actual = json_decode(json_encode($actual), true);
        $expected = [
            'host' => '127.0.0.1',
            'port' => 6379,
        ];
        $this->assertEquals($expected, $actual);
    }
}
