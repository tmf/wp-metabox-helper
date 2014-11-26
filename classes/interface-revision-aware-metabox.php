<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox;

use WP_Post;

/**
 * Interface RevisionAwareMetaboxInterface
 *
 * @package Tmf\Wordpress\Service\Metabox
 */
interface RevisionAwareMetaboxInterface
{
    /**
     * @return array
     */
    public function getRevisionFields();

    /**
     * @param $postId
     * @param $revisionId
     */
    public function restoreRevision($postId, $revisionId);

    /**
     * @param         $unchanged
     * @param WP_Post $revision
     * @param WP_Post $post
     * @return bool
     */
    public function checkForRevisionChanges($unchanged, WP_Post $revision, WP_Post $post);
} 