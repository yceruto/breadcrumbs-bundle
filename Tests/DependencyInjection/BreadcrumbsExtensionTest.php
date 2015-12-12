<?php

/*
 * This file is part of the BreadcrumbsBundle.
 *
 * (c) Yonel Ceruto <yonelceruto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yceruto\Bundle\BreadcrumbsBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yceruto\Bundle\BreadcrumbsBundle\DependencyInjection\BreadcrumbsExtension;

class BreadcrumbsExtensionTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var BreadcrumbsExtension
     */
    private $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.debug', false);
        $this->extension = new BreadcrumbsExtension();
    }

    public function testLoadDefault()
    {
        $this->container->getParameterBag()->add(array('kernel.root_dir' => __DIR__));

        $this->extension->load(array(), $this->container);

        $this->assertTrue($this->container->has('breadcrumbs_builder'));
        $this->assertEquals(
            '@Breadcrumbs/breadcrumbs/breadcrumbs.html.twig',
            $this->container->getParameter('breadcrumbs_template')
        );
    }

    public function testLoadOverrideTemplate()
    {
        $this->container->getParameterBag()->add(array('kernel.root_dir' => __DIR__ . '/Fixtures'));

        $this->extension->load(array(), $this->container);

        $this->assertTrue($this->container->has('breadcrumbs_builder'));
        $this->assertEquals(
            'breadcrumbs/breadcrumbs.html.twig',
            $this->container->getParameter('breadcrumbs_template')
        );
    }
}
