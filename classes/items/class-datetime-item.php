<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox\Item;

use Moment\Moment;

use Tmf\Wordpress\Service\Metabox\GenerateUrlTrait,
    Tmf\Wordpress\Service\Metabox\SingleMetaValueItemRevisionAwareTrait;

use WP_Post;

/**
 * Class DateTimeItem
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
class DateTimeItem extends TwigTemplateItemRenderer implements MetaboxItemInterface, RevisionAwareItemInterface
{
    use SingleMetaValueItemRevisionAwareTrait,
        GenerateUrlTrait;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_replace_recursive(['config' => [
            'format' => 'Y/m/d H:i',
            'mask' => true,
            'allowBlank' => true
        ]], $options);
        add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
    }

    /**
     * @param WP_Post $post
     * @return string
     */
    public function getItemTemplate(WP_Post $post)
    {
        return 'datetime.twig';
    }

    /**
     * @param WP_Post $post
     * @param array   $revisions
     * @return array
     */
    public function getItemTemplateContext(WP_Post $post, array $revisions = [])
    {
        $value = get_post_meta($post->ID, $this->getKey(), true);
        if($value){
            $value =  date_i18n($this->options['config']['format'], strtotime($value));
        }

        return array_merge([
            'key'    => $this->getKey(),
            'value'  => $value,
            'config' => $this->options['config']
        ], $this->options);
    }

    /**
     * @param WP_Post $post
     * @param WP_Post $revision
     */
    public function persist(WP_Post $post, WP_Post $revision)
    {
        $value = $_REQUEST[$this->getKey()];

        $format = 'Y-m-d H:i:s';
        if(isset($this->options['config']['timepicker']) && $this->options['config']['timepicker']==false){
            $format = 'Y-m-d';
        }

        update_metadata('post', $revision->ID, $this->getKey(), date_i18n($format, strtotime($value)));
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

    /**
     * @param string $adminPage
     */
    public function enqueueAssets($adminPage)
    {
        if (in_array($adminPage, array('post.php', 'post-new.php'))) {
            wp_enqueue_script('jquery-datetimepicker', $this->generateVendorUrl('bower_components/datetimepicker/jquery.datetimepicker.js'), array('jquery'));
            wp_enqueue_style('jquery-datetimepicker', $this->generateVendorUrl('bower_components/datetimepicker/jquery.datetimepicker.css'));
            wp_enqueue_style('jquery-datetimepicker-wordpress', $this->generateBaseUrl('resources/datetimepicker.wordpress.css'), array('jquery-datetimepicker'));
        }
    }
} 