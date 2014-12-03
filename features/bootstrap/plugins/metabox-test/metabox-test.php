<?php
/**
 * Plugin Name: Metabox Helper Test
 * Plugin URI: http://github.com/tmf/wp-metabox-helper
 * Description: A Test Plugin for the WordPress Metaboxhelper service
 * Version: 0.1
 * Author: Tom Forrer
 * Author URI: http://githubm.com/tmf
 * License: MIT
 */

/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */
if(!file_exists(__DIR__ . '/base')){
    symlink(__DIR__ . '/../../../..', __DIR__ . '/base');
}

if (file_exists( __DIR__ . '/base/vendor/autoload.php')) {
    require_once __DIR__ . '/base/vendor/autoload.php';
}

use Tmf\Wordpress\Service\Metabox\MetaboxServiceProvider,
    Tmf\Wordpress\Service\Metabox\Metabox,
    Tmf\Wordpress\Service\Metabox\Item\InputItem,
    Tmf\Wordpress\Service\Metabox\Item\DropdownItem,
    Tmf\Wordpress\Service\Metabox\Item\EditorItem;

$services = new Pimple\Container();

// $plugin contains the path of this plugin as wordpress sees it
$services->register(new MetaboxServiceProvider(), ['metaboxes.base_directory' => dirname($plugin) . '/base', 'metaboxes.vendor_directory' => dirname($plugin) . '/base/vendor']);

add_action('admin_init', function () use ($services) {
    $services['metaboxes']['foo'] = new Metabox('Foo', ['post'], 'normal', 'high');
    $services['metaboxes']['foo']['text'] = new InputItem(['label' => 'Metatext', 'description' => 'Some description']);
    $services['metaboxes']['foo']['dropdown'] = new DropdownItem(['multiple' => false, 'label' => 'Dropdown',  'options' => [['label' => 'Foo', 'value'=>'foo'], ['label' => 'ASDF', 'value'=>'asdf']]]);
    $services['metaboxes']['foo']['editor'] = new EditorItem(['label' => 'Editor']);
});