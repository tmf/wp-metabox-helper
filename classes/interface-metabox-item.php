<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox\Item;

use Tmf\Wordpress\Service\Metabox\KeyAwareInterface;
use WP_Post;

/**
 * Interface MetaboxItemInterface
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
interface MetaboxItemInterface extends KeyAwareInterface
{
    /**
     * @param WP_Post         $post
     * @param array|WP_Post[] $revisions
     * @return string
     */
    public function render(WP_Post $post, array $revisions = []);

    /**
     * @param WP_Post $post
     * @param WP_Post $revision
     */
    public function persist(WP_Post $post, WP_Post $revision);
} 