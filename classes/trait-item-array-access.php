<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox\Item;

use Tmf\Wordpress\Container\ContainerAwareInterface,
    Tmf\Wordpress\Container\ContainerAwareTrait;

use Pimple\Container;

use ArrayAccess,
    UnexpectedValueException;

/**
 * Trati ItemArrayAccess
 *
 * @package Tmf\Wordpress\Service\Metabox
 */
trait ItemArrayAccessTrait
{
    use ContainerAwareTrait;

    /**
     * @var array|MetaboxItemInterface[]
     */
    protected $items = [];

    /**
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * @param mixed $key
     * @return void|MetaboxItemInterface
     */
    public function offsetGet($key)
    {
        if (isset($this->items)) {
            return $this->items[$key];
        }
    }

    /**
     * @param mixed                $key
     * @param MetaboxItemInterface $item
     */
    public function offsetSet($key, $item)
    {
        if (!($item instanceof MetaboxItemInterface)) {
            throw new UnexpectedValueException('the argument must be a \Tmf\Wordpress\Service\Metabox\Metabox');
        }
        if ($item instanceof ContainerAwareInterface) {
            $item->setContainer($this->getContainer());
        }
        $item->setKey($key);
        $this->items[$key] = $item;
    }

    /**
     * @param mixed $key
     */
    public function offsetUnset($key)
    {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        }
    }
} 