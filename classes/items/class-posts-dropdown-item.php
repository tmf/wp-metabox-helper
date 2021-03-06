<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox\Item;

use WP_Post;

/**
 * Class PostsDropdownItem
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
class PostsDropdownItem extends DropdownItem
{
    /**
     *
     */
    public function setupRevisionField()
    {
        $filter = '_wp_post_revision_field_' . $this->getKey();
        add_filter($filter, function ($value, $field, WP_Post $post, $direction) {
            $fieldValue = get_post_meta($post->ID, $field, false);
            $posts = get_posts(['posts_per_page' => -1, 'post_type' => 'any', 'ignore_sticky_posts'=> true, 'post__in' => $fieldValue]);
            $postLabels = array_map(function($postId) use ($posts){
                $currentPost = reset(array_filter($posts, function(WP_Post $post) use($postId){
                    return $post->ID == $postId;
                }));
                return sprintf('"%s" (ID: %d)', $currentPost->post_title, $currentPost->ID);
            }, $fieldValue);

            return sprintf('[ %s ]', implode(', ', $postLabels));
        }, 10, 100);
    }


    public function getItemTemplate(WP_Post $post)
    {
        return 'posts-dropdown.twig';
    }

    /**
     * @param string $adminPage
     */
    public function enqueueAssets($adminPage)
    {
        if (in_array($adminPage, array('post.php', 'post-new.php'))) {
            wp_enqueue_script('selectize', $this->generateVendorUrl('bower_components/selectize/dist/js/standalone/selectize.js'), ['jquery']);
            wp_enqueue_script('selectize-wp-post-item', $this->generateVendorUrl('bower_components/selectize-wp-post-item/dist/js/plugins.js'), ['selectize']);

            wp_enqueue_style('selectize', $this->generateBaseUrl('resources/selectize.wordpress.css'));
            wp_enqueue_style('selectize-wp-post-item', $this->generateVendorUrl('bower_components/selectize-wp-post-item/dist/css/plugins.css'), ['selectize']);
        }
    }
} 