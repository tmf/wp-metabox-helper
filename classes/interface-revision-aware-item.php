<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */


namespace Tmf\Wordpress\Service\Metabox\Item;

use WP_Post;

/**
 * Interface RevisionAwareItemInterface
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
interface RevisionAwareItemInterface
{
    /**
     * @return array
     */
    public function getRevisionField();

    /**
     *
     */
    public function setupRevisionField();

    /**
     * @param int $postId
     * @param int $revisionId
     */
    public function restoreRevision($postId, $revisionId);

    /**
     * @param WP_Post $revision
     * @param WP_Post $post
     * @return bool
     */
    public function compareRevision(WP_Post $revision, WP_Post $post);
} 