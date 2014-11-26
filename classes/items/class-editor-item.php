<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox\Item;

use Tmf\Wordpress\Service\Metabox\SingleMetaValueItemRevisionAwareTrait;

use WP_Post;

/**
 * Class EditorItem
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
class EditorItem extends TwigTemplateItemRenderer implements MetaboxItemInterface, RevisionAwareItemInterface
{
    use SingleMetaValueItemRevisionAwareTrait;

    protected $settings = [];

    public function __construct($settings = [])
    {
        $this->settings = $settings;
    }

    public function getItemTemplate(WP_Post $post)
    {
        return 'editor.twig';
    }

    public function getItemTemplateContext(WP_Post $post, array $revisions = [])
    {
        return array_merge([
            'key'    => $this->getKey(),
            'editor' => $this->getEditor(get_post_meta($post->ID, $this->getKey(), true), $this->settings)
        ], $this->settings);
    }

    public function persist(WP_Post $post, WP_Post $revision)
    {
        update_metadata('post', $revision->ID, $this->getKey(), $_POST[$this->getKey()]);
    }

    /**
     * @return array
     */
    public function getRevisionField()
    {
        $label = ucfirst($this->getKey());
        if (isset($this->settings['label'])) {
            $label = $this->settings['label'];
        }

        return [$this->getKey() => $label];
    }

    protected function getEditor($content = '', $settings = [])
    {
        ob_start();
        wp_editor($content, $this->getKey(), $settings);

        return ob_get_clean();
    }
} 