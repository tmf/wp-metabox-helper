<?php
/**
 * @autor Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox;

/**
 * Interface KeyAwareInterface
 *
 * @package Tmf\Wordpress\Service\Metabox
 */
interface KeyAwareInterface {
    /**
     * @return string
     */
    public function getKey();

    /**
     * @param  string $key
     * @return string
     */
    public function setKey($key);
} 