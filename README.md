# configurator-toolkit

Extension for centralized management of your project configs

[![Build Status](https://secure.travis-ci.org/unforge/configurator-toolkit.svg?branch=master)](https://secure.travis-ci.org/unforge/configurator-toolkit)
[![Coverage Status](https://coveralls.io/repos/github/unforge/configurator-toolkit/badge.svg?branch=master)](https://coveralls.io/github/unforge/configurator-toolkit?branch=master)
[![License](https://poser.pugx.org/unforge/configurator-toolkit/license.svg)](https://packagist.org/packages/unforge/configurator-toolkit)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
    php composer.phar require unforge/configurator-toolkit
```

or add

```
    "unforge/configurator-toolkit": "dev-master"
```

to the require section of your `composer.json` file.

Basic Usage
------------

1) To initialize the class somewhere in the project loader:

from array

```php
    $config = [
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
    ];

    $configurator = new \Unforge\Toolkit\Configurator($config);
```

or [file](tests/resources/conf/config.php)

```php
    $configurator = new \Unforge\Toolkit\Configurator('tests/resources/conf/config.php');
```

2) Call the method of getting the config, for example for ** Db ** class

```php
    $conf = $configurator->db;

    $db = new \Db($conf->host, $conf->port, $conf->user, $conf->passowrd, $conf->database);
```

or initialize config inside class

```php
    class Db
    {
        private $connect;

        public function __constructor(\Unforge\Toolkit\Configurator $configurator)
        {
            if (!$this->connect) {
                $conf = $configurator->getConfigByObject($this);

                $this->connect($conf['host'], $conf['port'], $conf['user'], $conf['password'], $conf['database']);
            }
        }

        protected function connect($host, $port, $user, $password, $database)
        {
            // Init **Db** connect
        }
    }
```

`Note!` In the example described above, the config for **Db** class was implicitly received. In this case, the [getConfigByObject()](src/Configurator.php#L80-L86) method was used to convert the class name in \ Db to db and took the config for the class using this key

```php
	$conf = $configurator->getConfigByObject($this);
```

the obvious call will be the same

```php
    class Db
    {
        private $connect;

        public function __constructor(\Unforge\Toolkit\Configurator $configurator)
        {
            if (!$this->connect) {
                $conf = $configurator->db;

                $this->connect($conf['host'], $conf['port'], $conf['user'], $conf['password'], $conf['database']);
            }
        }

        protected function connect($host, $port, $user, $password, $database)
        {
            // Init **Db** connect
        }
    }
```