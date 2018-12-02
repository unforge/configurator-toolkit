<?php

namespace Unforge\ToolkitTests\ConfiguratorTests;

use Unforge\Toolkit\Configurator;

/**
 * Class TempNotExistObject
 * @package Unforge\ToolkitTests
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
