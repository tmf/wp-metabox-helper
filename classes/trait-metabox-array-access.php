<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox;

use Tmf\Wordpress\Container\ContainerAwareTrait;
use UnexpectedValueException;

/**
 * Trait MetaboxArrayAccess
 *
 * @package Tmf\Wordpress\Service\Metabox
 */
trait MetaboxArrayAccessTrait
{
    use ContainerAwareTrait;

    /**
     * @var array|MetaboxInterface[]
     */
    protected $metaboxes = [];

    /**
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->metaboxes[$key]);
    }

    /**
     * @param mixed $key
     * @return void|Metabox
     */
    public function offsetGet($key)
    {
        if (isset($this->metaboxes)) {
            return $this->metaboxes[$key];
        }
    }

    /**
     * @param mixed   $key
     * @param Metabox $metabox
     */
    public function offsetSet($key, $metabox)
    {
        if (!($metabox instanceof MetaboxInterface)) {
            // only allow metaboxes
            throw new UnexpectedValueException('the argument must implement \Tmf\Wordpress\Service\Metabox\MetaboxInterface');
        }
        $metabox->setKey($key);
        $metabox->setContainer($this->getContainer());
        $this->metaboxes[$key] = $metabox;
    }

    /**
     * @param mixed $key
     */
    public function offsetUnset($key)
    {
        if (isset($this->metaboxes[$key])) {
            // remove the metabox from wordpress for all supported post types
            foreach ($this->metaboxes[$key]->getPostTypes() as $postType) {
                remove_meta_box($key, $postType, $this->metaboxes[$key]->getContext());
            }

            unset($this->metaboxes[$key]);
        }
    }
} 