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
class TextareaItem extends TwigTemplateItemRenderer implements MetaboxItemInterface, RevisionAwareItemInterface
{
    use SingleMetaValueItemRevisionAwareTrait;

    protected $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function getItemTemplate(WP_Post $post)
    {
        return 'textarea.twig';
    }

    public function getItemTemplateContext(WP_Post $post, array $revisions = [])
    {
        return array_merge([
            'key'   => $this->getKey(),
            'value' => get_post_meta($post->ID, $this->getKey(), true)
        ], $this->options);
    }

    public function persist(WP_Post $post, WP_Post $revision)
    {
        update_metadata('post', $post->ID, $this->getKey(), $_POST[$this->getKey()]);
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