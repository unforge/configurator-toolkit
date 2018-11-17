<?php

namespace Unforge\Tests\ConfiguratorToolkit;

use \Unforge\ConfiguratorToolkit\Configurator;

/**
 * Class TempNotExistObject
 * @package Unforge\Tests\ConfiguratorToolkit
 */
class TempNotExistObject
{
    /**
     * @var Configurator
     */
    private $configurator;

    /**
     * TempObject constructor.
     * @param Configurator $configurator
     */
    public function __construct(Configurator $configurator)
    {
        $this->configurator = $configurator;
    }

    /**
     * @return \ArrayObject|array
     * @throws \ReflectionException
     */
    public function getConfig()
    {
        return $this->configurator->getConfigByObject($this);
    }
}
