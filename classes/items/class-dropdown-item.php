<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox\Item;

use Tmf\Wordpress\Service\Metabox\GenerateUrlTrait,
    Tmf\Wordpress\Service\Metabox\MultiMetaValueItemRevisionAwareTrait;
use WP_Post;

/**
 * Class DropdownItem
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
class DropdownItem extends TwigTemplateItemRenderer implements MetaboxItemInterface, RevisionAwareItemInterface
{
    use MultiMetaValueItemRevisionAwareTrait,
        GenerateUrlTrait;
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

        // resolve options if it is a callback
        $this->resolveOptions($post, $revisions);

        return array_merge([
            'key'      => $this->getKey(),
            'values'   => $values,
            'multiple' => true,
        ], $this->parameters);
    }

    /**
     * @param WP_Post $post
     * @param array   $revisions
     */
    protected function resolveOptions(WP_Post $post, array $revisions = [])
    {
        if (is_callable($this->parameters['options'])) {
            $this->parameters['options'] = call_user_func_array($this->parameters['options'], [$post, $revisions]);
        }
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
            wp_enqueue_script('selectize', $this->generateVendorUrl('bower_components/selectize/dist/js/standalone/selectize.min.js'), array('jquery'));
            wp_enqueue_style('selectize', $this->generateBaseUrl('resources/selectize.wordpress.css'));
        }
    }


} 