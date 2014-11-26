<?php
/**
 * @autor     Tom Forrer <tom.forrer@gmail.com>
 * @copyright Copyright (c) 2014 Tom Forrer (http://github.com/tmf)
 */


namespace Tmf\Wordpress\Service\Metabox\Item;

use Tmf\Wordpress\Container\ContainerAwareInterface;
use Tmf\Wordpress\Container\ContainerAwareTrait;
use Tmf\Wordpress\Service\Metabox\KeyAwareTrait;
use Twig_Environment;
use WP_Post;

/**
 * Class TwigTemplateItemRenderer
 *
 * @package Tmf\Wordpress\Service\Metabox\Item
 */
abstract class TwigTemplateItemRenderer implements MetaboxItemInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;
    use KeyAwareTrait;

    /**
     * @param WP_Post $post
     * @param array|WP_Post[] $revisions
     * @return string
     */
    public function render(WP_Post $post, array $revisions = [])
    {
        return $this->getTwig()->render(
            $this->getItemTemplate($post),
            $this->getItemTemplateContext($post, $revisions)
        );
    }

    /**
     * @param WP_Post $post
     * @param array|WP_Post[] $revisions
     * @return array
     */
    abstract public function getItemTemplateContext(WP_Post $post, array $revisions = []);

    /**
     * @param WP_Post $post
     * @return string
     */
    abstract public function getItemTemplate(WP_Post $post);

    /**
     * @return Twig_Environment
     */
    protected function getTwig()
    {
        return $this->getContainer()['metaboxes.twig'];
    }
} 