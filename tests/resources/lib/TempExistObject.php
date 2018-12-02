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

namespace Unforge\ToolkitTests\ConfiguratorTests;

use \Unforge\Toolkit\Configurator;

/**
 * Class TempExistObject
 * @package Unforge\ToolkitTests
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
