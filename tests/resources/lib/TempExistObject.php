<?php

namespace Unforge\Tests\ConfiguratorToolkit;

use \Unforge\ConfiguratorToolkit\Configurator;

/**
 * Class TempExistObject
 * @package Unforge\Tests\ConfiguratorToolkit
 */
class TempExistObject
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
