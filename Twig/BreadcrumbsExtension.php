<?php

/*
 * This file is part of the BreadcrumbsBundle.
 *
 * (c) Yonel Ceruto <yonelceruto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yceruto\Bundle\BreadcrumbsBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BreadcrumbsExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_breadcrumbs', array($this, 'renderBreadcrumbs'), array('is_safe' => array('html'), 'needs_environment' => true))
        );
    }

    public function renderBreadcrumbs(\Twig_Environment $twig)
    {
        $breadcrumbs = $this->container->get('breadcrumbs_builder')->create();
        $template = $this->container->getParameter('breadcrumbs_template');

        return $twig->render($template, array('breadcrumbs' => $breadcrumbs));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'breadcrumbs_extension';
    }
}
