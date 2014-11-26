<?php
/**
 * @autor Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox;

use WP_Post;

/**
 * Class SingleMetaItemRevisionAwareTrait
 *
 * @package Tmf\Wordpress\Service\Metabox
 */
trait SingleMetaValueItemRevisionAwareTrait {

    use KeyAwareTrait;

    /**
     * @return array
     */
    public function getRevisionField()
    {
        $label = ucfirst($this->getKey());

        return [$this->getKey() => $label];
    }

    /**
     *
     */
    public function setupRevisionField()
    {
        $filter = '_wp_post_revision_field_' . $this->getKey();
        add_filter($filter, function ($value, $field, WP_Post $post, $direction) {
            $fieldValue = get_post_meta($post->ID, $field, true);

            return $fieldValue;
        }, 10, 100);

    }

    /**
     * @param WP_Post $revision
     * @param WP_Post $post
     * @return bool
     */
    public function compareRevision(WP_Post $revision, WP_Post $post)
    {
        $key = $this->getKey();
        $revisionValue = get_post_meta($revision->ID, $key, true);
        if (isset($_REQUEST[$key])) {
            $revisionValue = $_REQUEST[$key];
        }
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore' && isset($_REQUEST['revision'])) {
            $revisionValue = get_post_meta(intval($_REQUEST['revision']), $key, true);
            add_action('_wp_put_post_revision', function ($revisionId) use ($key, $revisionValue) {
                update_metadata('post', $revisionId, $key, $revisionValue);
            });
        }

        $postValue = get_post_meta($post->ID, $key, true);

        return $revisionValue === $postValue;
    }

    /**
     * @param $postId
     * @param $revisionId
     */
    public function restoreRevision($postId, $revisionId)
    {
        $revisionValue = get_post_meta($revisionId, $this->getKey(), true);
        update_metadata('post', $postId, $this->getKey(), $revisionValue);
    }
} 