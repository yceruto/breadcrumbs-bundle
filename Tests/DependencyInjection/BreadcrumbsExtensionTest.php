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

    /**
     * @dataProvider getData
     */
    public function testLoadTemplate($rootDir, $templatePath)
    {
        $this->container->getParameterBag()->add(array('kernel.root_dir' => $rootDir));
        $this->extension->load(array(), $this->container);
        $this->assertTrue($this->container->has('breadcrumbs_builder'));
        $this->assertEquals($templatePath, $this->container->getParameter('breadcrumbs_template'));
    }

    /**
     * Get data provider
     *
     * @return array
     */
    public function getData()
    {
        return array(
            array(__DIR__, '@Breadcrumbs/breadcrumbs/breadcrumbs.html.twig'),
            array(__DIR__.'/Fixtures', 'breadcrumbs/breadcrumbs.html.twig'),
        );
    }
}
