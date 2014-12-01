<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox\Item;

use Tmf\Wordpress\Service\Metabox\MultiMetaValueItemRevisionAwareTrait;
use WP_Post;

/**
 * Class DropdownItem
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
class DropdownItem extends TwigTemplateItemRenderer implements MetaboxItemInterface, RevisionAwareItemInterface
{
    use MultiMetaValueItemRevisionAwareTrait;
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        if (isset($parameters['options'])) {
            $this->parameters = $parameters;
        } else {
            $this->parameters = ['options' => $parameters];
        }

        add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
    }

    /**
     * @param WP_Post $post
     * @return string
     */
    public function getItemTemplate(WP_Post $post)
    {
        return 'select.twig';
    }

    /**
     * @param WP_Post $post
     * @param array   $revisions
     * @return array
     */
    public function getItemTemplateContext(WP_Post $post, array $revisions = [])
    {
        $values = get_post_meta($post->ID, $this->getKey(), false);

        return array_merge([
            'key'      => $this->getKey(),
            'values'   => $values,
            'multiple' => true,
        ], $this->parameters);
    }

    /**
     * @param WP_Post $post
     * @param WP_Post $revision
     */
    public function persist(WP_Post $post, WP_Post $revision)
    {
        $key = $this->getKey();
        $values = $_POST[$key];
        if (is_array($values)) {
            delete_metadata('post', $revision->ID, $key);
            foreach ($values as $value) {
                add_metadata('post', $revision->ID, $key, $value);
            }
        } else {
            if (empty($values)) {
                delete_post_meta($revision->ID, $key);
            } else {
                update_metadata('post', $revision->ID, $key, $values);
            }
        }
    }

    /**
     * @param string $adminPage
     */
    public function enqueueAssets($adminPage)
    {
        if (in_array($adminPage, array('post.php', 'post-new.php'))) {
            wp_enqueue_script('selectize', $this->generateUrl('vendor/bower-asset/selectize/dist/js/standalone/selectize.min.js'), array('jquery'));
            wp_enqueue_style('selectize', $this->generateUrl('resources/selectize.wordpress.css'));
        }
    }

    /**
     * @param string $path
     * @return string
     */
    protected function generateUrl($path = '')
    {
        $container = $this->getContainer();
        $baseDirectory = $container['metaboxes.base_directory'];

        return get_site_url(null, str_replace(ABSPATH, '', $baseDirectory) . '/' . $path);
    }
} 