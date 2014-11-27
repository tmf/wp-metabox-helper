<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox\Item;

use Tmf\Wordpress\Service\Metabox\SingleMetaValueItemRevisionAwareTrait;
use WP_Post;

/**
 * Class TextItem
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
class InputItem extends TwigTemplateItemRenderer implements MetaboxItemInterface, RevisionAwareItemInterface
{
    use SingleMetaValueItemRevisionAwareTrait;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge(['type' => 'text'], $options);
    }

    /**
     * @param WP_Post $post
     * @return string
     */
    public function getItemTemplate(WP_Post $post)
    {
        return 'input.twig';
    }

    /**
     * @param WP_Post $post
     * @param array   $revisions
     * @return array
     */
    public function getItemTemplateContext(WP_Post $post, array $revisions = [])
    {
        return array_merge([
            'key'   => $this->getKey(),
            'value' => get_post_meta($post->ID, $this->getKey(), true)
        ], $this->options);
    }

    /**
     * @param WP_Post $post
     * @param WP_Post $revision
     */
    public function persist(WP_Post $post, WP_Post $revision)
    {
        $value = $_REQUEST[$this->getKey()];
        update_metadata('post', $revision->ID, $this->getKey(), $value);
    }

    /**
     * @return array
     */
    public function getRevisionField()
    {
        $label = ucfirst($this->getKey());
        if (isset($this->options['label'])) {
            $label = $this->options['label'];
        }

        return [$this->getKey() => $label];
    }
}