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

use \ReflectionClass;

/**
 * Class Configurator
 * @package Unforge\Toolkit
 */
class Configurator implements \ArrayAccess
{
    private $config;

    /**
     * Configurator constructor.
     *
     * @param string|array $resource
     */
    public function __construct($resource)
    {
        if (is_array($resource)) {
            $this->config = $resource;
        } else {
            if (gettype($resource) != 'string') {
                throw new \LogicException(
                    'Unexpected type ' . gettype($resource) . ', expected string'
                );
            }

            if (!file_exists($resource)) {
                throw new \LogicException(
                    "File " . var_export($resource, true) . " not exist"
                );
            }

            $this->config = include($resource);
        }
    }

    /**
     * @param $offset
     *
     * @return \ArrayObject|array
     */
    public function __get($offset)
    {
        if (isset($this->config[$offset])) {
            return $this->$offset = new \ArrayObject($this->config[$offset], \ArrayObject::ARRAY_AS_PROPS);
        }

        return [];
    }

    /**
     * @param $offset
     *
     * @return \ArrayObject|array
     */
    public function get($offset)
    {
        return $this->$offset;
    }

    /**
     * @param object $object
     *
     * @return \ArrayObject|array
     * @throws \ReflectionException
     */
    public function getConfigByObject($object)
    {
        if (!is_object($object)) {
            throw new \LogicException(
                'Unexpected type ' . gettype($object) . ', expected object'
            );
        }

        $offset = $this->getOffsetNameByClassName(
            (new ReflectionClass($object))->getName()
        );

        return $this->get($offset);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getOffsetNameByClassName(string $name): string
    {
        $name = explode("\\", $name);
        return strtolower(preg_replace('/[A-Z]/', '_\0', lcfirst(array_pop($name))));
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset) || isset($this->config[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (isset($this->$offset)) {
            if (is_array($value)) {
                $this->$offset = $value + $this->$offset->getArrayCopy();
            } else {
                throw new \LogicException('Unexpected type ' . gettype($value) . ', expected array');
            }
        } else {
            $this->config[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
}
