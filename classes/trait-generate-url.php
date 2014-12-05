<?php
/**
 * @autor Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */


namespace Tmf\Wordpress\Service\Metabox;


use Tmf\Wordpress\Container\ContainerAwareTrait;

trait GenerateUrlTrait {
    use ContainerAwareTrait;

    /**
     * @param string $path
     * @return string
     */
    protected function generateBaseUrl($path = '')
    {
        $container = $this->getContainer();
        $baseDirectory = $container['metaboxes.base_directory'];

        return get_site_url(null, str_replace(ABSPATH, '', $baseDirectory) . '/' . $path);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function generateVendorUrl($path = '')
    {
        $container = $this->getContainer();
        $vendorDirectory = $container['metaboxes.vendor_directory'];

        return get_site_url(null, str_replace(ABSPATH, '', $vendorDirectory) . '/' . $path);
    }
} 