<?php

/*
 * This file is part of the BreadcrumbsBundle.
 *
 * (c) Yonel Ceruto <yonelceruto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Twig;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yceruto\Bundle\BreadcrumbsBundle\Breadcrumbs;
use Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsBuilder;
use Yceruto\Bundle\BreadcrumbsBundle\DependencyInjection\BreadcrumbsExtension;
use Yceruto\Bundle\BreadcrumbsBundle\Twig\BreadcrumbsExtension as TwigBreadcrumbsExtension;

class BreadcrumbsExtensionTest extends TestCase
{
    public function testRenderDefaultBreadcrumbsAndTemplate()
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('/', 'home');

        $breadcrumbsBuilder = $this->getMockBuilder('Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $breadcrumbsBuilder->method('createFromRequest')
            ->willReturn($breadcrumbs);

        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);
        $container->set('breadcrumbs_builder', $breadcrumbsBuilder);

        $extension = new BreadcrumbsExtension();
        $extension->load(array(), $container);

        /* @var BreadcrumbsBuilder $breadcrumbsBuilder */
        $twigExtension = new TwigBreadcrumbsExtension($breadcrumbsBuilder);
        $this->assertEquals('breadcrumbs_extension', $twigExtension->getName());
        $this->assertInternalType('array', $twigExtension->getFunctions());

        $loader = new \Twig_Loader_Filesystem();
        $loader->addPath(__DIR__.'/Fixtures/Resources/views', 'Breadcrumbs');
        $environment = new \Twig_Environment($loader);

        $content = $twigExtension->renderBreadcrumbs($environment);
        $this->assertContains('<ol class="breadcrumb"><li class="active">Home</li></ol>', $content);
    }

    public function testRenderCustomBreadcrumbsAndTemplate()
    {
        $breadcrumbsBuilder = $this->getMockBuilder('Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);
        $container->set('breadcrumbs_builder', $breadcrumbsBuilder);

        $extension = new BreadcrumbsExtension();
        $extension->load(array(), $container);

        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->add('/', 'home');

        /* @var BreadcrumbsBuilder $breadcrumbsBuilder */
        $twigExtension = new TwigBreadcrumbsExtension($breadcrumbsBuilder);

        $loader = new \Twig_Loader_Filesystem();
        $loader->addPath(__DIR__.'/Fixtures/Resources/BreadcrumbsBundle/views', 'Breadcrumbs');
        $environment = new \Twig_Environment($loader);

        $content = $twigExtension->renderBreadcrumbs($environment, $breadcrumbs);
        $this->assertContains('<ol class="custom-breadcrumb"><li class="active">HOME</li></ol>', $content);
    }
}
