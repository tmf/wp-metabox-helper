<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox;

use Tmf\Wordpress\Container\ContainerAwareInterface;

use Twig_Loader_Chain,
    Twig_Loader_Filesystem,
    Twig_Environment;

use WP_Post;

use ArrayAccess;

/**
 * Class MetaboxHelper
 *
 * @package Tmf\Wordpress\Service
 */
class MetaboxHelper implements ArrayAccess, ContainerAwareInterface
{
    use MetaboxArrayAccessTrait;

    /**
     * @param string $postType
     */
    public function addMetaboxes($postType)
    {
        // only add metaboxes supporting the current post type
        foreach ($this->filter([$postType]) as $key => $metabox) {
            add_meta_box(
                $key,                   // id
                $metabox->getTitle(),   // title
                [$metabox, 'render'],   // callback
                $postType,              // post_type
                $metabox->getContext(), // context
                $metabox->getPriority() // priority
            );
        }
    }

    /**
     *
     */
    public function initializeItemTemplating()
    {
        $container = $this->getContainer();

        if (!isset($container['metaboxes.base_directory'])) {
            $container['metaboxes.base_directory'] = dirname(__DIR__);
        }

        $container['metaboxes.twig.loader'] = function ($container) {
            $templateDirectory = $container['metaboxes.base_directory'] . DIRECTORY_SEPARATOR . 'templates';

            return new Twig_Loader_Chain([new Twig_Loader_Filesystem($templateDirectory)]);
        };
        $container['metaboxes.twig'] = function ($container) {
            return new Twig_Environment($container['metaboxes.twig.loader']);
        };
    }

    /**
     * @param int     $postId
     * @param WP_Post $post
     */
    public function persistMetaboxes($postId, $post)
    {
        $revision = $post;
        $parentId = wp_is_post_revision($postId);
        if ($parentId !== false) {
            $post = get_post($parentId);
        }
        // only persist metaboxes supporting the cuurren post type
        foreach ($this->filter([$post->post_type]) as $key => $metabox) {
            $metabox->persist($post, $revision);
        }
    }

    /**
     * @param string $adminPage
     */
    public function enqueueAssets($adminPage)
    {
        if (in_array($adminPage, array('post.php', 'post-new.php'))) {
            wp_enqueue_style('metaboxes', $this->generateUrl('resources/metaboxes-backend.css'));
        }
    }

    /**
     * @param array $fields
     * @return array
     */
    public function getRevisionFields($fields)
    {
        foreach ($this->filter([get_post_type($_REQUEST['post_id'])]) as $key => $metabox) {
            $fields = array_merge($fields, $metabox->getRevisionFields());
        }

        return $fields;
    }

    /**
     * @param bool    $unchanged
     * @param WP_Post $revision
     * @param WP_Post $post
     * @return bool
     */
    public function checkForRevisionChanges($unchanged, WP_Post $revision, WP_Post $post)
    {
        foreach ($this->filter([$post->post_type]) as $key => $metabox) {
            $unchanged = $unchanged && $metabox->checkForRevisionChanges($unchanged, $revision, $post);
        }

        return $unchanged;
    }

    /**
     * @param int $postId
     * @param int $revisionId
     */
    public function restorePostRevision($postId, $revisionId)
    {
        foreach ($this->filter([get_post_type($postId)]) as $key => $metabox) {
            $metabox->restoreRevision($postId, $revisionId);
        }
    }

    /**
     * @param WP_Post $post
     * @return WP_Post
     */
    public function setupPreview(WP_Post $post){
        $preview = wp_get_post_autosave($post->ID);
        add_filter('get_post_metadata', function( $check, $object_id, $meta_key, $single) use($preview){
            global $wp_current_filter;
            end($wp_current_filter);
            if(count($wp_current_filter) == 1 || prev($wp_current_filter) != 'get_post_metadata'){
                return get_post_meta($preview->ID, $meta_key, $single);
            }
            return $check;
        }, 10, 100);

        return $post;
    }

    /**
     * @param array $postTypes
     * @return array|MetaboxInterface[]
     */
    public function filter(array $postTypes = [])
    {
        // return a filtered array of metaboxes which support the given post types
        return array_filter($this->metaboxes, function (MetaboxInterface $metabox) use ($postTypes) {

            // check if there are intersections between the given post types and the metaboxe's post types
            return 0 < count(array_intersect($metabox->getPostTypes(), $postTypes));
        });
    }

    /**
     * @param string $path
     * @return string
     */
    protected  function generateUrl($path = '')
    {
        $container = $this->getContainer();
        $baseDirectory = $container['metaboxes.base_directory'];

        return get_site_url(null, str_replace(ABSPATH, '', $baseDirectory) . '/' . $path);
    }
}