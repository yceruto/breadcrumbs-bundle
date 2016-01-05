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

use Yceruto\Bundle\BreadcrumbsBundle\Breadcrumbs;
use Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsBuilder;

class BreadcrumbsExtension extends \Twig_Extension
{
    /**
     * @var BreadcrumbsBuilder
     */
    private $breadcrumbsBuilder;

    /**
     * @var string
     */
    private $template;

    public function __construct(BreadcrumbsBuilder $breadcrumbsBuilder, $template)
    {
        $this->breadcrumbsBuilder = $breadcrumbsBuilder;
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_breadcrumbs', array($this, 'renderBreadcrumbs'), array('is_safe' => array('html'), 'needs_environment' => true)),
        );
    }

    public function renderBreadcrumbs(\Twig_Environment $twig, Breadcrumbs $breadcrumbs = null, $template = null)
    {
        if (null === $breadcrumbs) {
            $breadcrumbs = $this->breadcrumbsBuilder->createFromRequest();
        }

        if (null === $template) {
            $template = $this->template;
        }

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
