WordPress Metabox Helper Service
================================

[![Build Status](https://travis-ci.org/tmf/wp-metabox-helper.svg?branch=master)](https://travis-ci.org/tmf/wp-metabox-helper)

A [Pimple](http://pimple.sensiolabs.org/) service based on the [Hookable Service Provider](https://github.com/tmf/wp-hookable-service) that facilitates the creation of metaboxes for post meta
fields. The items in a metabox representing post meta fields are compatible with WordPress revisions and autosaves.
This service can be registered in a pimple container and be used in WordPress themes or plugins. 

This metabox helper service comes with the following types of items out of the box:
* Text inputs
* Dropdown fields (implemented with [selectize](http://brianreavis.github.io/selectize.js/))
* Textareas
* TinyMCE Editors

Usage
-----

This service is installable via [Composer](https://getcomposer.org/) and relies on it's class autoloading mechanism. You can package the vendor
directory with you theme or plugin, with your WordPress installation or with a setup of your choosing.

1. Create a composer project for your plugin or theme:
    
    ```bash
    cd your-plugin-directory
    # install composer phar
    curl -sS https://getcomposer.org/installer | php
    # create a basic composer.json
    ./composer.phar init
    ```
2. Add the metabox helper service as a dependency in your composer.json
    
    ```bash
    ./composer.phar require tmf/wp-metabox-helper ~0.1
    ```
3. Create a pimple container and register the metabox helper service
    
    ```php
    // load the vendors via composer autoload
    if (file_exists( __DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    }
    
    use Tmf\Wordpress\Service\Metabox\MetaboxServiceProvider,
        Tmf\Wordpress\Service\Metabox\Metabox,
        Tmf\Wordpress\Service\Metabox\Item\InputItem,
        Tmf\Wordpress\Service\Metabox\Item\DropdownItem,
        Tmf\Wordpress\Service\Metabox\Item\EditorItem;
    
    // create the service container
    $services = new Pimple\Container();
    
    // register the metabox helper with this service provider. the service is registered with the 'metaboxes' key
    $services->register(new MetaboxServiceProvider());
    ```
4. Add a metabox and some metabox items representing post meta values
    
    ```
    add_action('admin_init', function () use ($services) {
        // create a metabox for 'post' post types
        $services['metaboxes']['foo'] = new Metabox('Foo', ['post'], 'normal', 'high');
        
        // add item: the key is the post meta key
        $services['metaboxes']['foo']['text'] = new InputItem(['label' => 'Metatext', 'description' => 'Some description']);
        $services['metaboxes']['foo']['dropdown'] = new DropdownItem([['label' => 'Foo', 'value'=>'foo'], ['label' => 'Bar', 'value'=>'bar']]);
        $services['metaboxes']['foo']['editor'] = new EditorItem();
    });
    ```
