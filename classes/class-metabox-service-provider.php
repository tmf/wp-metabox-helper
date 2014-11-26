<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox;

use Tmf\Wordpress\Container\HookableServiceProvider;

/**
 * Class MetaboxServiceProvider
 *
 * @package Tmf\Wordpress\Service\Metabox
 */
class MetaboxServiceProvider extends HookableServiceProvider
{
    /**
     * @param string $serviceKey
     */
    public function __construct($serviceKey = 'metaboxes')
    {
        //
        parent::__construct(
            $serviceKey,
            '\Tmf\Wordpress\Service\Metabox\MetaboxHelper',
            [[
                 'hook'     => 'add_meta_boxes',
                 'method'   => 'initializeItemTemplating',
                 'priority' => 90 // initialize twig before adding the metaboxes
             ], [
                 'hook'     => 'add_meta_boxes',
                 'method'   => 'addMetaboxes',
                 'priority' => 100
             ], [
                 'hook'   => 'save_post',
                 'method' => 'persistMetaboxes'
             ], [
                 'hook'   => 'admin_enqueue_scripts',
                 'method' => 'enqueueAssets'
             ], [
                 'hook'   => '_wp_post_revision_fields',
                 'method' => 'getRevisionFields'
             ], [
                 'hook'   => 'wp_restore_post_revision',
                 'method' => 'restorePostRevision'
             ], [
                 'hook'   => 'wp_save_post_revision_check_for_changes',
                 'method' => 'checkForRevisionChanges'
             ], [
                 'hook'   => 'the_preview',
                 'method' => 'setupPreview'
             ]]
        );
    }
} 