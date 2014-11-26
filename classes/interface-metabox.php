<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */


namespace Tmf\Wordpress\Service\Metabox;

use Tmf\Wordpress\Container\ContainerAwareInterface;
use ArrayAccess;
use WP_Post;

/**
 * Interface MetaboxInterface
 *
 * @package Tmf\Wordpress\Service\Metabox
 */
interface MetaboxInterface extends ArrayAccess, ContainerAwareInterface, KeyAwareInterface, RevisionAwareMetaboxInterface
{
    /**
     * @param WP_Post $post
     */
    public function render(WP_Post $post);

    /**
     * @param WP_Post $post
     * @param WP_Post $revision
     */
    public function persist(WP_Post $post, WP_Post $revision);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return array
     */
    public function getPostTypes();

    /**
     * @return string
     */
    public function getContext();

    /**
     * @return string
     */
    public function getPriority();


} 