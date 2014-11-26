<?php
/**
 * @autor Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */


namespace Tmf\Wordpress\Service\Metabox;

trait KeyAwareTrait {
    /**
     * @var string
     */
    protected $key;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

} 