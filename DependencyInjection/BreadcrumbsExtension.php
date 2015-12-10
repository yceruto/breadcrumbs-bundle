<?php

/*
 * This file is part of the BreadcrumbsBundle.
 *
 * (c) Yonel Ceruto <yonelceruto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yceruto\Bundle\BreadcrumbsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BreadcrumbsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $templatesDir = $container->getParameter('kernel.root_dir') . '/Resources/views';
        if (file_exists($templatesDir . '/breadcrumbs/breadcrumbs.html.twig')) {
            $template = 'breadcrumbs/breadcrumbs.html.twig';
        } else {
            $template = '@Breadcrumbs/breadcrumbs/breadcrumbs.html.twig';
        }
        $container->setParameter('breadcrumbs_template', $template);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
