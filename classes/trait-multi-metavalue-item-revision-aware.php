<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox;

use WP_Post;

/**
 * Class SingleMetaItemRevisionAwareTrait
 *
 * @package Tmf\Wordpress\Service\Metabox
 */
trait MultiMetaValueItemRevisionAwareTrait
{

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
            $fieldValue = get_post_meta($post->ID, $field, false);

            return sprintf('[ %s ]', implode(', ', $fieldValue));
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
        $revisionValues = get_post_meta($revision->ID, $key, false);
        if (isset($_REQUEST[$key])) {
            $revisionValues = $_REQUEST[$key];
        }
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore' && isset($_REQUEST['revision'])) {

            $revisionValues = get_post_meta(intval($_REQUEST['revision']), $key, false);
            add_action('_wp_put_post_revision', function ($revisionId) use ($key, $revisionValues) {
                delete_metadata('post', $revisionId, $key);
                foreach ($revisionValues as $revisionValue) {
                    add_metadata('post', $revisionId, $key, $revisionValue);
                }

            });
        }

        $postValues = get_post_meta($post->ID, $key, false);

        // strict comparison: keys, values and sequence must be identical
        return $revisionValues === $postValues;
    }

    /**
     * @param $postId
     * @param $revisionId
     */
    public function restoreRevision($postId, $revisionId)
    {
        $key = $this->getKey();
        $revisionValues = get_post_meta($revisionId, $key, false);
        delete_metadata('post', $revisionId, $key);
        foreach ($revisionValues as $revisionValue) {
            add_metadata('post', $revisionId, $key, $revisionValue);
        }
    }
} 