<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */

namespace Tmf\Wordpress\Service\Metabox\Item;

use Tmf\Wordpress\Service\Metabox\KeyAwareTrait;
use WP_Post;

/**
 * Class ContentItem
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
class ContentItem extends TwigTemplateItemRenderer implements MetaboxItemInterface
{
    use KeyAwareTrait;

    protected $parameters = [];

    public function __construct($parameters = [])
    {
        if (!is_array($parameters)) {
            $parameters = ['content' => $parameters];
        }
        $this->parameters = $parameters;
    }

    public function getItemTemplate(WP_Post $post)
    {
        return 'content.twig';
    }

    public function getItemTemplateContext(WP_Post $post, array $revisions = [])
    {
        $content = $this->parameters['content'];
        if (is_callable($content)) {
            $content = call_user_func_array($content, [$post, $this->getKey()]);
        }

        return array_merge($this->parameters, [
            'content' => $content
        ]);
    }

    public function persist(WP_Post $post, WP_Post $revision)
    {

    }
} 