<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox;

use Tmf\Wordpress\Service\Metabox\Item\ItemArrayAccessTrait,
    Tmf\Wordpress\Service\Metabox\Item\MetaboxItemInterface,
    Tmf\Wordpress\Service\Metabox\Item\RevisionAwareItemInterface;
use WP_Post;

/**
 * Class Metabox
 *
 * @package Tmf\Wordpress\Service
 */
class Metabox implements MetaboxInterface
{
    use ItemArrayAccessTrait;
    use KeyAwareTrait;

    /**
     * @var string the metabox title
     */
    protected $title = '';

    /**
     * @var array supported wordpress post types
     */
    protected $postTypes = [];

    /**
     * @var string The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side').
     */
    protected $context = 'advanced';

    /**
     * @var string The priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
     */
    protected $priority = 'default';

    /**
     * @param string $title
     * @param array  $postTypes
     * @param string $context
     * @param string $priority
     */
    public function __construct($title = '', array $postTypes = [], $context = 'advanced', $priority = 'default')
    {
        $this->title = $title;
        $this->postTypes = $postTypes;
        $this->context = $context;
        $this->priority = $priority;
    }

    /**
     * @param WP_Post $post
     */
    public function render(WP_Post $post)
    {
        $revisions = wp_get_post_revisions($post->ID);
        $nonceField = wp_nonce_field($this->getKey() . '_meta_box', $this->getKey() . '_meta_box_nonce', true, false);
        $items = array_reduce($this->items, function ($html, MetaboxItemInterface $item) use ($post, $revisions) {
            return $html . $item->render($post, $revisions);
        }, '');
        echo $nonceField . $items;
    }

    /**
     * @param WP_Post $post
     * @param WP_Post $revision
     */
    public function persist(WP_Post $post, WP_Post $revision)
    {
        if ($this->verifyRequest($post)) {
            foreach ($this->items as $item) {
                $item->persist($post, $revision);
            }
        }
    }

    /**
     * @return array
     */
    public function getRevisionFields()
    {
        return array_reduce($this->items, function ($fields, MetaboxItemInterface $item) {
            if ($item instanceof RevisionAwareItemInterface) {
                $item->setupRevisionField();
                $fields = array_merge($fields, $item->getRevisionField());
            }

            return $fields;
        }, []);
    }

    /**
     * @param bool    $unchanged
     * @param WP_Post $revision
     * @param WP_Post $post
     * @return bool
     */
    public function checkForRevisionChanges($unchanged, WP_Post $revision, WP_Post $post)
    {
        foreach ($this->items as $item) {
            if ($unchanged && $item instanceof RevisionAwareItemInterface) {
                $unchanged = $item->compareRevision($revision, $post);
            }
        }

        return $unchanged;
    }

    /**
     * @param $postId
     * @param $revisionId
     */
    public function restoreRevision($postId, $revisionId)
    {
        foreach ($this->items as $item) {
            if ($item instanceof RevisionAwareItemInterface) {
                $item->restoreRevision($postId, $revisionId);
            }
        }
    }

    /**
     * @param WP_Post $post
     * @return bool
     */
    protected function verifyRequest(WP_Post $post)
    {
        return
            isset($_POST[$this->getKey() . '_meta_box_nonce']) &&
            wp_verify_nonce($_POST[$this->getKey() . '_meta_box_nonce'], $this->getKey() . '_meta_box');
    }

    /**
     * @return array
     */
    public function getPostTypes()
    {
        return $this->postTypes;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }
} 