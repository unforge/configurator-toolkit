<?php

return [
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
